<?php

namespace Database\Factories;

use App\Models\Project;
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
        $project = Project::all()->random(); // Choose a random existing project

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(2),
            'project_id' => $project->id, // Assign the chosen project ID
            'estimated_completion_time' => fake()->numberBetween(1, 8),
            'task_points' => fake()->numberBetween(1, 10),
            'due_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'assigned_to' => $project->employers()->inRandomOrder()->first()->id,
        ];
    }
}
