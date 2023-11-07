<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

      $superAdmin = Role::where('code', 'super-admin')->firstOrFail();
      $admin = Role::where('code', 'admin')->firstOrFail();
      $teacher = Role::where('code', 'teacher')->firstOrFail();
      $student = Role::where('code', 'student')->firstOrFail();

     $superAdminAcc = User::create(['name' => 'super admin', 
                    'email' => 'superadmin@gmail.com',
                    'password' => Hash::make('password')])->assignRole($superAdmin);

     $adminAcc = User::create(['name' => 'admin', 
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('password'),
                    'user_id' => 1,
                    ])->assignRole($admin);

     $teacher = User::create(['name' => 'teacher', 
                    'email' => 'teacher@gmail.com',
                    'password' => Hash::make('password'),
                    'user_id' => 2])->assignRole($teacher);
    }
}
