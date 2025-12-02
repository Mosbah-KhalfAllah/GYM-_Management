<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\WorkoutProgram;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendances.
     */
    public function index()
    {
        $coachId = auth()->id();
        
        // Récupérer les membres assignés au coach
        $memberIds = User::whereHas('programs', function ($query) use ($coachId) {
            $query->where('coach_id', $coachId);
        })->pluck('id');
        
        $attendances = Attendance::whereIn('user_id', $memberIds)
            ->with('user')
            ->latest()
            ->paginate(20);
        
        $todayCount = Attendance::whereIn('user_id', $memberIds)
            ->whereDate('check_in', today())
            ->count();
        
        $weekCount = Attendance::whereIn('user_id', $memberIds)
            ->whereBetween('check_in', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();
        
        return view('coach.attendance.index', compact('attendances', 'todayCount', 'weekCount'));
    }

    /**
     * Display the scanner page.
     */
    public function scanner()
    {
        return view('coach.attendance.scanner');
    }

    /**
     * Process QR code scan for attendance.
     */
    public function scan(Request $request)
    {
        $validated = $request->validate([
            'qr_data' => 'required|string',
        ]);

        try {
            $data = json_decode($validated['qr_data'], true);
            
            if (!$data || !isset($data['user_id']) || !isset($data['type']) || $data['type'] !== 'member_access') {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code invalide'
                ]);
            }

            $user = User::find($data['user_id']);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Membre non trouvé'
                ]);
            }

            // Vérifier que le membre est assigné au coach
            $coachId = auth()->id();
            $isAssigned = $user->programs()
                ->where('coach_id', $coachId)
                ->wherePivot('status', 'active')
                ->exists();
            
            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce membre n\'est pas assigné à votre supervision'
                ]);
            }

            // Vérifier si le membre a déjà une présence active
            $activeAttendance = Attendance::where('user_id', $user->id)
                ->whereNull('check_out')
                ->whereDate('check_in', today())
                ->first();

            if ($activeAttendance) {
                // Enregistrer la sortie
                $activeAttendance->update([
                    'check_out' => now(),
                    'duration_minutes' => $activeAttendance->check_in->diffInMinutes(now()),
                ]);
                
                return response()->json([
                    'success' => true,
                    'action' => 'check_out',
                    'user' => $user,
                    'message' => 'Sortie enregistrée pour ' . $user->full_name
                ]);
            } else {
                // Enregistrer l'entrée
                Attendance::create([
                    'user_id' => $user->id,
                    'check_in' => now(),
                    'entry_method' => 'qr_code',
                ]);
                
                return response()->json([
                    'success' => true,
                    'action' => 'check_in',
                    'user' => $user,
                    'message' => 'Entrée enregistrée pour ' . $user->full_name
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mark an attendance as checked out (manual action by coach).
     */
    public function checkout(Request $request, Attendance $attendance)
    {
        $coachId = auth()->id();

        // Ensure the attendance belongs to a member assigned to this coach
        $member = $attendance->user;
        if (!$member) {
            return back()->with('error', 'Membre introuvable.');
        }

        $isAssigned = $member->programs()
            ->where('coach_id', $coachId)
            ->wherePivot('status', 'active')
            ->exists();

        if (!$isAssigned) {
            return back()->with('error', 'Ce membre n\'est pas assigné à votre supervision.');
        }

        if ($attendance->check_out) {
            return back()->with('info', 'Cette présence a déjà une sortie.');
        }

        $attendance->update([
            'check_out' => now(),
            'duration_minutes' => $attendance->check_in->diffInMinutes(now()),
        ]);

        return back()->with('success', 'Sortie enregistrée pour ' . $member->full_name);
    }

    /**
     * Force a check-in for a member (create attendance) — manual action by coach.
     */
    public function forceCheckIn(Request $request, User $member)
    {
        $coachId = auth()->id();

        // Verify assignment
        $isAssigned = $member->programs()
            ->where('coach_id', $coachId)
            ->wherePivot('status', 'active')
            ->exists();

        if (!$isAssigned) {
            return back()->with('error', 'Ce membre n\'est pas assigné à votre supervision.');
        }

        // Check for existing active attendance today
        $active = Attendance::where('user_id', $member->id)
            ->whereNull('check_out')
            ->whereDate('check_in', today())
            ->first();

        if ($active) {
            return back()->with('info', 'Le membre a déjà une entrée active aujourd\'hui.');
        }

        Attendance::create([
            'user_id' => $member->id,
            'check_in' => now(),
            'entry_method' => 'manual',
        ]);

        return back()->with('success', 'Entrée forcée enregistrée pour ' . $member->full_name);
    }
}