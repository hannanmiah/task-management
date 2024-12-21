<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(3),
            'due_date' => $this->faker->date('Y-m-d'),
            'status' => $this->faker->randomElement(['pending', 'completed', 'in-progress']),
        ];
    }
}
