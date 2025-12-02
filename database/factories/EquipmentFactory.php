<?php
// database/factories/EquipmentFactory.php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Cardio', 'Strength', 'Weight', 'Functional', 'Accessory'];
        $statuses = ['available', 'in_use', 'maintenance', 'broken'];
        
        return [
            'name' => $this->faker->word() . ' Machine',
            'category' => $this->faker->randomElement($categories),
            'serial_number' => 'EQ' . $this->faker->unique()->numerify('#######'),
            'purchase_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'last_maintenance_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'next_maintenance_date' => $this->faker->dateTimeBetween('now', '+6 months'),
            'status' => $this->faker->randomElement($statuses),
            'notes' => $this->faker->sentence(),
            'location' => $this->faker->randomElement(['Main Area', 'Cardio Zone', 'Weight Room', 'Studio']),
            'purchase_price' => $this->faker->randomFloat(2, 500, 5000),
            'assigned_to' => User::where('role', 'coach')->inRandomOrder()->first()->id ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}