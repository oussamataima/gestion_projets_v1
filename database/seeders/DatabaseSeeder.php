<?php

namespace Database\Seeders;

use App\Models\Profession;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(ProfessionSeeder::class);
        // $this->call(SkillSeeder::class);
        // User::factory(100)->create();
        // Project::factory(50)->create();
        Task::factory(330)->create();
    }
}

