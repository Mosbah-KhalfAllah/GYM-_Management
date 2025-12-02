<?php
// database/factories/ChallengeFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ChallengeFactory extends Factory
{
    public function definition(): array
    {
        $types = ['attendance', 'workout', 'class', 'weight_loss', 'muscle_gain'];
        
        return [
            'title' => $this->faker->words(4, true) . ' Challenge',
            'description' => $this->faker->paragraphs(2, true),
            'type' => $this->faker->randomElement($types),
            'target_value' => $this->faker->numberBetween(10, 50),
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'points_reward' => $this->faker->numberBetween(100, 1000),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}