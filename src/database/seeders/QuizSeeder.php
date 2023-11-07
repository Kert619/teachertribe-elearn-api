<?php

namespace Database\Seeders;

use App\Models\Phase;
use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phases = Phase::all();

        foreach ($phases as $phase) {
            Quiz::insert([
                ['phase_id' => $phase->id, 'name' => '1', 'created_at' => now(), 'updated_at' => now() ],
                ['phase_id' => $phase->id, 'name' => '2', 'created_at' => now(), 'updated_at' => now() ],
            ]);
        }
    }
}
