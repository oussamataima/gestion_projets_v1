<?php

namespace Database\Seeders;

use App\Models\Profession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Profession::count()) {
            return;
        }

        Profession::insert([
            
                ['name' => 'Front-End Developer'],
                ['name' => 'Back-End Developer'],
                ['name' => 'Full-Stack Developer'],
                ['name' => 'Mobile Developer'],
                ['name' => 'DevOps Engineer'],
                ['name' => 'Data Scientist'],
                ['name' => 'Software Engineer'],
                ['name' => 'Quality Assurance Engineer (QA)'],
                ['name' => 'UI/UX Designer'],
                ['name' => 'Web Developer'],
                ['name' => 'Cloud Architect'],
                ['name' => 'Machine Learning Engineer'],
                ['name' => 'Security Engineer'],
                ['name' => 'Database Administrator (DBA)'],
              
        ]);
    }
}
