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
        Permission::create(['name' => 'create-courses']);
        Permission::create(['name' => 'edit-courses']);
        Permission::create(['name' => 'delete-courses']);
        Permission::create(['name' => 'view-courses']);
        Permission::create(['name' => 'enroll-in-courses']);
        Permission::create(['name' => 'update-profile']);
        Permission::create(['name' => 'view-statistics']);

    }
}
