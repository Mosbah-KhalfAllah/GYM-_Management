<?php
// database/seeders/DatabaseSeeder.php - Version corrigée

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WorkoutProgram;
use App\Models\ClassModel;
use App\Models\Challenge;
use App\Models\Equipment;
use App\Models\Membership;
use App\Models\Attendance;
use App\Models\ClassBooking;
use App\Models\ChallengeParticipant;
use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\MemberProgram;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        User::create([
            'role' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'Gym',
            'email' => 'admin@gym.com',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'birth_date' => '1980-05-15',
            'gender' => 'male',
            'address' => '123 Rue Principale, Paris, France',
            'emergency_contact' => '+33612345678',
            'is_active' => true,
        ]);

        // Create 2 coaches
        User::create([
            'role' => 'coach',
            'first_name' => 'Coach',
            'last_name' => 'One',
            'email' => 'coach1@gym.com',
            'password' => Hash::make('password'),
            'phone' => '0123456788',
            'birth_date' => '1990-08-20',
            'gender' => 'male',
            'address' => '456 Avenue Fitness, Lyon, France',
            'emergency_contact' => '+33612345679',
            'is_active' => true,
        ]);

        User::create([
            'role' => 'coach',
            'first_name' => 'Coach',
            'last_name' => 'Two',
            'email' => 'coach2@gym.com',
            'password' => Hash::make('password'),
            'phone' => '0123456787',
            'birth_date' => '1992-03-12',
            'gender' => 'female',
            'address' => '789 Boulevard Sport, Marseille, France',
            'emergency_contact' => '+33612345680',
            'is_active' => true,
        ]);

        // Create 5 members
        $members = [];
        $firstNames = ['Jean', 'Marie', 'Pierre', 'Sophie', 'Luc'];
        $lastNames = ['Dupont', 'Martin', 'Bernard', 'Laurent', 'Simon'];
        $genders = ['male', 'female', 'male', 'female', 'male'];
        $addresses = [
            '10 Rue de la Paix, Paris, France',
            '20 Avenue du Commerce, Toulouse, France',
            '30 Boulevard des Arts, Nice, France',
            '40 Chemin du Lac, Nantes, France',
            '50 Place de la République, Strasbourg, France',
        ];
        
        for ($i = 1; $i <= 5; $i++) {
            $members[] = User::create([
                'role' => 'member',
                'first_name' => $firstNames[$i-1],
                'last_name' => $lastNames[$i-1],
                'email' => "member{$i}@gym.com",
                'password' => Hash::make('password'),
                'phone' => '0123456' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'birth_date' => now()->subYears(rand(20, 55))->format('Y-m-d'),
                'gender' => $genders[$i-1],
                'address' => $addresses[$i-1],
                'emergency_contact' => '06' . rand(10000000, 99999999),
                'is_active' => true,
            ]);
        }

        // Create 3 workout programs
        $programs = [];
        for ($i = 1; $i <= 3; $i++) {
            $programs[] = WorkoutProgram::create([
                'coach_id' => $i == 1 ? 2 : 3, // coach1 or coach2
                'title' => "Programme Fitness {$i}",
                'description' => "Description du programme fitness {$i} pour atteindre vos objectifs.",
                'level' => $i == 1 ? 'beginner' : ($i == 2 ? 'intermediate' : 'advanced'),
                'duration_days' => [30, 60, 90][$i-1],
                'goal' => $i == 1 ? 'weight_loss' : ($i == 2 ? 'muscle_gain' : 'endurance'),
                'is_active' => true,
            ]);
        }

        // Create 4 classes
        $classes = [];
        for ($i = 1; $i <= 4; $i++) {
            $classes[] = ClassModel::create([
                'name' => ["Yoga", "Zumba", "CrossFit", "HIIT"][$i-1] . " Class",
                'description' => "Description de la classe " . ["Yoga", "Zumba", "CrossFit", "HIIT"][$i-1],
                'coach_id' => $i <= 2 ? 2 : 3,
                'schedule_time' => now()->addDays($i)->setTime(9 + $i, 0, 0),
                'duration_minutes' => 60,
                'capacity' => 20,
                'registered_count' => rand(5, 15),
                'status' => 'scheduled',
                'location' => $i % 2 == 0 ? 'Room A' : 'Room B',
                'price' => rand(10, 30),
            ]);
        }

        // Create 2 challenges
        $challenges = [];
        for ($i = 1; $i <= 2; $i++) {
            $challenges[] = Challenge::create([
                'title' => "Challenge Mensuel {$i}",
                'description' => "Participez à notre challenge mensuel {$i} et gagnez des points!",
                'type' => $i == 1 ? 'attendance' : 'workout',
                'target_value' => $i == 1 ? 12 : 20,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'points_reward' => $i == 1 ? 500 : 1000,
                'is_active' => true,
            ]);
        }

        // Create 5 equipment items
        for ($i = 1; $i <= 5; $i++) {
            Equipment::create([
                'name' => ["Tapis de course", "Vélo elliptique", "Haltères", "Machine à pectoraux", "Rameur"][$i-1],
                'category' => $i <= 2 ? 'Cardio' : 'Strength',
                'serial_number' => 'EQ' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'purchase_date' => now()->subMonths(rand(1, 24)),
                'last_maintenance_date' => now()->subMonths(rand(1, 6)),
                'next_maintenance_date' => now()->addMonths(rand(1, 6)),
                'status' => 'available',
                'location' => $i <= 2 ? 'Cardio Zone' : 'Weight Room',
                'purchase_price' => rand(1000, 5000),
            ]);
        }

        // Create memberships for members
        foreach ($members as $index => $member) {
            Membership::create([
                'user_id' => $member->id,
                'type' => ['Mensuel', 'Annuel', 'Premium'][$index % 3],
                'price' => [30, 300, 50][$index % 3],
                'start_date' => now()->subMonths(rand(1, 6)),
                'end_date' => now()->addMonths([1, 12, 1][$index % 3]),
                'status' => 'active',
                'auto_renewal' => true,
            ]);
        }

        // Create attendances
        foreach ($members as $member) {
            for ($day = 1; $day <= 7; $day++) {
                $checkIn = now()->subDays($day)->setTime(rand(8, 18), rand(0, 59), 0);
                Attendance::create([
                    'user_id' => $member->id,
                    'check_in' => $checkIn,
                    'check_out' => $checkIn->copy()->addMinutes(rand(60, 120)),
                    'duration_minutes' => rand(60, 120),
                    'entry_method' => 'qr_code',
                ]);
            }
        }

        // Book classes for members
        foreach ($members as $member) {
            foreach ($classes as $class) {
                if (rand(0, 1)) {
                    ClassBooking::create([
                        'class_id' => $class->id,
                        'member_id' => $member->id,
                        'booking_code' => 'BK' . strtoupper(uniqid()),
                        'status' => 'confirmed',
                    ]);
                }
            }
        }

        // Join challenges
        foreach ($members as $member) {
            foreach ($challenges as $challenge) {
                ChallengeParticipant::create([
                    'challenge_id' => $challenge->id,
                    'member_id' => $member->id,
                    'current_progress' => rand(0, $challenge->target_value),
                    'points_earned' => rand(0, $challenge->points_reward),
                    'completed' => rand(0, 1),
                ]);
            }
        }

        // Create exercises for each program
        $exerciseData = [
            ['name' => 'Pompes', 'sets' => 3, 'reps' => 10, 'weight' => 0, 'rest_seconds' => 60],
            ['name' => 'Squats', 'sets' => 4, 'reps' => 12, 'weight' => 20, 'rest_seconds' => 90],
            ['name' => 'Développé couché', 'sets' => 3, 'reps' => 8, 'weight' => 30, 'rest_seconds' => 120],
            ['name' => 'Tractions', 'sets' => 3, 'reps' => 8, 'weight' => 0, 'rest_seconds' => 90],
            ['name' => 'Burpees', 'sets' => 3, 'reps' => 15, 'weight' => 0, 'rest_seconds' => 45],
            ['name' => 'Leg Press', 'sets' => 3, 'reps' => 15, 'weight' => 50, 'rest_seconds' => 90],
        ];

        $exercises = [];
        foreach ($programs as $program) {
            foreach ($exerciseData as $index => $exData) {
                $exercises[] = Exercise::create([
                    'program_id' => $program->id,
                    'name' => $exData['name'],
                    'description' => 'Exercice de ' . $exData['name'] . ' pour le programme ' . $program->title,
                    'sets' => $exData['sets'],
                    'reps' => $exData['reps'],
                    'weight' => $exData['weight'],
                    'rest_seconds' => $exData['rest_seconds'],
                    'day_number' => ($index % 6) + 1,
                    'video_url' => null,
                    'image_path' => null,
                ]);
            }
        }

        // Assign members to programs and create exercise logs
        foreach ($members as $memberIndex => $member) {
            foreach ($programs as $programIndex => $program) {
                // Assign member to program
                $memberProgram = MemberProgram::create([
                    'member_id' => $member->id,
                    'program_id' => $program->id,
                    'start_date' => now()->subMonths(rand(1, 2)),
                    'end_date' => now()->addMonths(1),
                    'current_day' => rand(5, 20),
                    'completed_exercises' => rand(10, 30),
                    'completion_percentage' => rand(30, 90),
                    'status' => 'active',
                ]);

                // Create exercise logs for this member program
                $programExercises = Exercise::where('program_id', $program->id)->get();
                foreach ($programExercises as $exercise) {
                    for ($log = 0; $log < 3; $log++) {
                        ExerciseLog::create([
                            'member_program_id' => $memberProgram->id,
                            'exercise_id' => $exercise->id,
                            'workout_date' => now()->subDays(rand(0, 10)),
                            'completed' => rand(0, 1) === 1,
                            'actual_sets' => $exercise->sets,
                            'actual_reps' => rand($exercise->reps - 2, $exercise->reps + 2),
                            'actual_weight' => $exercise->weight > 0 ? $exercise->weight + rand(-5, 5) : 0,
                            'notes' => 'Séance d\'entraînement complétée avec succès',
                            'rating' => rand(3, 5),
                        ]);
                    }
                }
            }
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('👑 Admin: admin@gym.com / password');
        $this->command->info('🏋️‍♂️ Coach 1: coach1@gym.com / password');
        $this->command->info('🏋️‍♂️ Coach 2: coach2@gym.com / password');
        $this->command->info('👤 Members: member1@gym.com à member5@gym.com / password');
    }
}