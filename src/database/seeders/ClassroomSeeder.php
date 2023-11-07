<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $webDev = Classroom::create([
            'name' => 'Web Development',
            'user_id' => 3,
        ]);

        $html = Course::find(1)->id;
        $css = Course::find(2)->id;

        $webDev->courses()->attach([$html, $css]);
    }
}
