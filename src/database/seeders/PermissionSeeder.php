<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createPermission = Permission::create([
            'name' => 'Create Permission', 'code' => 'can_create_permission', 'created_at' => now(), 'updated_at' => now()
        ]);

        $viewPermission = Permission::create( ['name' => 'View Permission', 'code' => 'can_view_permission', 'created_at' => now(), 'updated_at' => now()]);

        $updatePermission = Permission::create(['name' => 'Update Permission', 'code' => 'can_update_permission', 'created_at' => now(), 'updated_at' => now()]);

        $deletePermission = Permission::create(['name' => 'Delete Permission', 'code' => 'can_delete_permission', 'created_at' => now(), 'updated_at' => now()]);

        $createRole = Permission::create(['name' => 'Create Role', 'code' => 'can_create_role', 'created_at' => now(), 'updated_at' => now()]);

        $viewRole = Permission::create( ['name' => 'View Role', 'code' => 'can_view_role', 'created_at' => now(), 'updated_at' => now()]);

        $updateRole = Permission::create(['name' => 'Update Role', 'code' => 'can_update_role', 'created_at' => now(), 'updated_at' => now()]);

        $deleteRole = Permission::create(['name' => 'Delete Role', 'code' => 'can_delete_role', 'created_at' => now(), 'updated_at' => now()]);

        $createUser = Permission::create(['name' => 'Create User', 'code' => 'can_create_user', 'created_at' => now(), 'updated_at' => now()]);

        $viewUser = Permission::create(  ['name' => 'View User', 'code' => 'can_view_user', 'created_at' => now(), 'updated_at' => now()]);

        $updateUser = Permission::create(['name' => 'Update User', 'code' => 'can_update_user', 'created_at' => now(), 'updated_at' => now()]);

        $deleteUser = Permission::create( ['name' => 'Delete User', 'code' => 'can_delete_user', 'created_at' => now(), 'updated_at' => now()]);

        $assignRole = Permission::create(   ['name' => 'Assign Role', 'code' => 'can_assign_role', 'created_at' => now(), 'updated_at' => now()]);

        $assignPermission = Permission::create(  ['name' => 'Assign Permission', 'code' => 'can_assign_permission', 'created_at' => now(), 'updated_at' => now()]);

        $revokePermission = Permission::create(  ['name' => 'Revoke Permission', 'code' => 'can_revoke_permission', 'created_at' => now(), 'updated_at' => now()]);

        $createLesson =  Permission::create(  ['name' => 'Create Lesson', 'code' => 'can_create_lesson', 'created_at' => now(), 'updated_at' => now()]);

        $viewLesson =  Permission::create(  ['name' => 'View Lesson', 'code' => 'can_view_lesson', 'created_at' => now(), 'updated_at' => now()]);

        $updateLesson =  Permission::create(  ['name' => 'Update Lesson', 'code' => 'can_update_lesson', 'created_at' => now(), 'updated_at' => now()]);

        $deleteLesson =  Permission::create(  ['name' => 'Delete Lesson', 'code' => 'can_delete_lesson', 'created_at' => now(), 'updated_at' => now()]);

        $superAdmin = Role::where('code','super-admin')->firstOrFail();
        $admin = Role::where('code', 'admin')->firstOrFail();
        $teacher = Role::where('code','teacher')->firstOrFail();
        $student = Role::where('code', 'student')->firstOrFail();

        $admin->givePermissionTo([
            $createUser, $viewUser, $updateUser, $deleteUser, $assignRole, $createLesson, $viewLesson, $updateLesson, $deleteLesson
        ]);

        $teacher->givePermissionTo([$viewLesson]);
    }
}
