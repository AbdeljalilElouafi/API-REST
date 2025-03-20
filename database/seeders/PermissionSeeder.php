<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create-courses', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit-courses', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-courses', 'guard_name' => 'web']);
        Permission::create(['name' => 'view-courses', 'guard_name' => 'web']);
        Permission::create(['name' => 'enroll-in-courses', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-profile', 'guard_name' => 'web']);
        Permission::create(['name' => 'view-statistics', 'guard_name' => 'web']);

    }
}
