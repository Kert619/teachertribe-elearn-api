<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'code' => 'super-admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Admin', 'code' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Teacher', 'code' => 'teacher', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Student', 'code' => 'student', 'created_at' => now(), 'updated_at' => now()]
        ];

        Role::insert($roles);
    }
}
