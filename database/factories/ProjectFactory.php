<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company . ' Project',
            'description' => fake()->text(800),
            'start_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'due_date' => fake()->dateTimeBetween('+1 month', '+3 months'),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed']),
            'created_by' => $this->getRandomAdminId(),
            'assigned_to' => $this->getRandomManagerId(),
        ];
    }

    private function getRandomAdminId()
    {
        // Logic to find a random user with the "admin" role
        $adminUser = User::where('role', 'admin')->inRandomOrder()->first();
        if ($adminUser) {
            return $adminUser->id;
        } else {
            throw new \Exception('No admin user found to create project.');
        }
    }
    private function getRandomManagerId()
    {
        // Logic to find a random user with the "admin" role
        $managerUser = User::where('role', 'manager')->inRandomOrder()->first();
        if ($managerUser) {
            return $managerUser->id;
        } else {
            return null;
        }
    }

    public function configure()
    {
        return $this->afterCreating(function (Project $project) {
            $employers = User::where('role', 'employer')->inRandomOrder()->limit(5)->get()->pluck('id');
            $project->employers()->attach($employers);
        });
    }
}
