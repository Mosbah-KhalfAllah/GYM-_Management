<?php
// database/factories/WorkoutProgramFactory.php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkoutProgramFactory extends Factory
{
    public function definition(): array
    {
        $levels = ['beginner', 'intermediate', 'advanced'];
        $goals = ['weight_loss', 'muscle_gain', 'endurance', 'flexibility'];
        
        return [
            'coach_id' => User::where('role', 'coach')->inRandomOrder()->first()->id ?? User::factory(),
            'title' => $this->faker->words(3, true) . ' Program',
            'description' => $this->faker->paragraphs(3, true),
            'level' => $this->faker->randomElement($levels),
            'duration_days' => $this->faker->numberBetween(30, 90),
            'goal' => $this->faker->randomElement($goals),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}