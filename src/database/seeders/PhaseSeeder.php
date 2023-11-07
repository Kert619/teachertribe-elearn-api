<?php

namespace Database\Seeders;

use App\Models\Phase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Phase::insert([
            ['course_id' => 1, 'name' => 'Phase 1', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 1, 'name' => 'Phase 2', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 1, 'name' => 'Phase 3', 'created_at' => now(), 'updated_at' => now()],

            ['course_id' => 2, 'name' => 'Phase 1', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 2, 'name' => 'Phase 2', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 2, 'name' => 'Phase 3', 'created_at' => now(), 'updated_at' => now()],

            ['course_id' => 3, 'name' => 'Phase 1', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 3, 'name' => 'Phase 2', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 3, 'name' => 'Phase 3', 'created_at' => now(), 'updated_at' => now()],

            ['course_id' => 4, 'name' => 'Phase 1', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 4, 'name' => 'Phase 2', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 4, 'name' => 'Phase 3', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
