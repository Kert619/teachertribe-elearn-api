<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Phase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phases = Phase::all();

        foreach ($phases as $phase) {
          Level::insert([
            ['phase_id' => $phase->id, 'name' => '1', 'description'=> $phase->course->name, 'created_at' => now(), 'updated_at' => now()],
            ['phase_id' => $phase->id, 'name' => '2', 'description'=> $phase->course->name, 'created_at' => now(), 'updated_at' => now()],
            ['phase_id' => $phase->id, 'name' => '3', 'description'=> $phase->course->name, 'created_at' => now(), 'updated_at' => now()],
            ['phase_id' => $phase->id, 'name' => '4', 'description'=> $phase->course->name, 'created_at' => now(), 'updated_at' => now()],
            ['phase_id' => $phase->id, 'name' => '5', 'description'=> $phase->course->name, 'created_at' => now(), 'updated_at' => now()],
            ['phase_id' => $phase->id, 'name' => '6', 'description'=> $phase->course->name, 'created_at' => now(), 'updated_at' => now()],
            ['phase_id' => $phase->id, 'name' => '7', 'description'=> $phase->course->name, 'created_at' => now(), 'updated_at' => now()],
            ['phase_id' => $phase->id, 'name' => '8', 'description'=> $phase->course->name, 'created_at' => now(), 'updated_at' => now()],
            ['phase_id' => $phase->id, 'name' => '9', 'description'=> $phase->course->name, 'created_at' => now(), 'updated_at' => now()],
            ['phase_id' => $phase->id, 'name' => '10', 'description'=> $phase->course->name, 'created_at' => now(), 'updated_at' => now()],
          ]);
        }
    }
}
