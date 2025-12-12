<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Membership;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class NotifyExpiringMemberships extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:expiring-memberships {--days=3 : Number of days before expiry to notify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users and admins/coaches about memberships that will expire within the given number of days.';

    public function handle()
    {
        $days = (int) $this->option('days');
        if ($days <= 0) {
            $days = 3;
        }

        $now = Carbon::now();
        $cutoff = $now->copy()->addDays($days)->endOfDay();

        $this->info("Looking for memberships expiring on or before {$cutoff->toDateString()} (within {$days} days)");

        $memberships = Membership::where('status', 'active')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<=', $cutoff->toDateString())
            ->get();

        if ($memberships->isEmpty()) {
            $this->info('No expiring memberships found.');
            return 0;
        }

        // Notify each member and admins/coaches
        $adminsAndCoaches = User::whereIn('role', ['admin', 'coach'])->get();

        foreach ($memberships as $membership) {
            $member = $membership->user; // relation membership->user exists
            if (! $member) {
                continue;
            }

            $daysLeft = $now->diffInDays(Carbon::parse($membership->end_date), false);
            $readable = $membership->end_date ? Carbon::parse($membership->end_date)->toDateString() : 'N/A';

            $message = "Votre adhésion ({$membership->type}) expire le {$readable}";
            if ($daysLeft >= 0) {
                $message .= " (dans {$daysLeft} jours).";
            }

            // Create notification for the member
            Notification::create([
                'user_id' => $member->id,
                'type' => 'membership',
                'message' => $message,
                'data' => [
                    'membership_id' => $membership->id,
                    'end_date' => $membership->end_date,
                    'days_left' => $daysLeft,
                ],
            ]);

            $this->info("Notified member {$member->email} (membership id: {$membership->id})");

            // Notify admins and coaches
            foreach ($adminsAndCoaches as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'membership',
                    'message' => "Membre {$member->full_name} ({$member->email}) a une adhésion expirant le {$readable} (membership id: {$membership->id}).",
                    'data' => [
                        'membership_id' => $membership->id,
                        'member_id' => $member->id,
                        'end_date' => $membership->end_date,
                        'days_left' => $daysLeft,
                    ],
                ]);
            }

            $this->info("Notified " . $adminsAndCoaches->count() . " admins/coaches for membership {$membership->id}");
        }

        $this->info('Done.');

        return 0;
    }
}
