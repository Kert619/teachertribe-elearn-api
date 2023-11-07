<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'name' => 'HTML'
        ]);
        Course::create([
            'name' => 'CSS'
        ]);
        Course::create([
            'name' => 'JAVASCRIPT'
        ]);
        Course::create([
            'name' => 'PHP'
        ]);
    }
}
