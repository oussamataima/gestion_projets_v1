<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (Skill::count()) {
            return;
        }

        Skill::insert([
              ['name' => 'Laravel',],
              ['name' => 'PHP',],
              ['name' => 'JavaScript',],
              ['name' => 'HTML',],
              ['name' => 'CSS',],
              ['name' => 'MySQL'],
              ['name' => 'Git',],
              ['name' => 'React',],
              ['name' => 'Node.js',],
              ['name' => 'Python',],
              ['name' => 'Java',],
              ['name' => 'C++',],
              ['name' => 'C#' ],
              ['name' => 'Swift' ],
              ['name' => 'Kotlin' ],
              ['name' => 'Ruby' ],
              ['name' => 'Go' ],
              ['name' => 'RESTful APIs' ],
              ['name' => 'GraphQL' ],            
        ]);
    }
    
}
