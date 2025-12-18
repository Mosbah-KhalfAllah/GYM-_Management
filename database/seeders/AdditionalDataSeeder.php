<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Inquiry;
use App\Models\Equipment;
use App\Models\MaintenanceLog;

class AdditionalDataSeeder extends Seeder
{
    public function run(): void
    {
        $members = User::where('role', 'member')->get();
        $allUsers = User::all();
        $equipments = Equipment::all();

        // Create payments
        foreach ($members as $member) {
            for ($i = 0; $i < 3; $i++) {
                Payment::create([
                    'user_id' => $member->id,
                    'payment_id' => 'PAY_' . strtoupper(uniqid()),
                    'amount' => rand(20, 100),
                    'currency' => 'TND',
                    'payment_method' => ['cash', 'card', 'online'][rand(0, 2)],
                    'status' => ['completed', 'pending', 'failed'][rand(0, 2)],
                    'payment_gateway' => 'manual',
                    'description' => 'Paiement adhésion ' . ['mensuelle', 'trimestrielle', 'annuelle'][rand(0, 2)],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        // Skip notifications - using Laravel's default structure

        // Create inquiries
        for ($i = 0; $i < 5; $i++) {
            Inquiry::create([
                'first_name' => 'Visiteur',
                'last_name' => 'Numéro ' . ($i + 1),
                'email' => 'visiteur' . ($i + 1) . '@example.com',
                'phone' => '0612345' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'subject' => ['membership', 'classes', 'personal_training', 'other'][rand(0, 3)],
                'message' => 'Demande d\'informations concernant votre salle de sport.',
                'status' => ['pending', 'contacted', 'resolved'][rand(0, 2)],
                'created_at' => now()->subDays(rand(1, 20)),
            ]);
        }

        // Create maintenance logs
        foreach ($equipments as $equipment) {
            MaintenanceLog::create([
                'equipment_id' => $equipment->id,
                'maintenance_date' => now()->subDays(rand(1, 60)),
                'type' => ['preventive', 'corrective', 'emergency'][rand(0, 2)],
                'description' => 'Maintenance effectuée sur ' . $equipment->name,
                'cost' => rand(50, 500),
                'next_maintenance_date' => now()->addDays(rand(30, 180)),
            ]);
        }

        $this->command->info('✅ Additional data seeded successfully!');
    }
}