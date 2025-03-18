<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Role;
use App\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

       
        $admin = Role::where('name', 'admin')->first();
        $mentor = Role::where('name', 'mentor')->first();
        $student = Role::where('name', 'student')->first();

        $admin->givePermissionTo([
            'create-courses',
            'edit-courses',
            'delete-courses',
            'view-courses',
            'view-statistics',
        ]);

        $mentor->givePermissionTo([
            'create-courses',
            'edit-courses',
            'delete-courses',
            'view-courses',
            'update-profile',
        ]);

        $student->givePermissionTo([
            'view-courses',
            'enroll-in-courses',
            'update-profile',
        ]);
    }
}

