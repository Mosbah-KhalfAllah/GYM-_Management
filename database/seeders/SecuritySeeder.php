<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SecuritySeeder extends Seeder
{
    public function run(): void
    {
        // Admin par défaut sécurisé
        User::firstOrCreate(
            ['email' => 'admin@gym.local'],
            [
                'first_name' => 'Admin',
                'last_name' => 'System',
                'role' => 'admin',
                'password' => Hash::make('Admin@2024!'),
                'is_active' => true,
            ]
        );

        // Coach de test
        User::firstOrCreate(
            ['email' => 'coach@gym.local'],
            [
                'first_name' => 'Coach',
                'last_name' => 'Test',
                'role' => 'coach',
                'password' => Hash::make('Coach@2024!'),
                'is_active' => true,
            ]
        );

        // Membre de test
        User::firstOrCreate(
            ['email' => 'member@gym.local'],
            [
                'first_name' => 'Member',
                'last_name' => 'Test',
                'role' => 'member',
                'password' => Hash::make('Member@2024!'),
                'is_active' => true,
            ]
        );
    }
}