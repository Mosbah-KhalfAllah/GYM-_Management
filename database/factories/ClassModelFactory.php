<?php
// database/factories/ClassModelFactory.php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassModelFactory extends Factory
{
    public function definition(): array
    {
        $classTypes = ['Yoga', 'Zumba', 'CrossFit', 'HIIT', 'Pilates', 'Spinning', 'Boxing', 'Karate'];
        
        return [
            'name' => $this->faker->randomElement($classTypes) . ' Class',
            'description' => $this->faker->paragraphs(2, true),
            'coach_id' => User::where('role', 'coach')->inRandomOrder()->first()->id ?? User::factory(),
            'schedule_time' => $this->faker->dateTimeBetween('+1 days', '+30 days'),
            'duration_minutes' => $this->faker->randomElement([60, 75, 90, 120]),
            'capacity' => $this->faker->numberBetween(10, 30),
            'registered_count' => $this->faker->numberBetween(0, 30),
            'status' => 'scheduled',
            'location' => $this->faker->randomElement(['Room A', 'Room B', 'Main Hall', 'Outdoor']),
            'price' => $this->faker->randomFloat(2, 10, 50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}