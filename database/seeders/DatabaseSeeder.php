<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $randArray = [null, 1,2,3,4,5,7,8,9,11,12,15,16,20];

        Category::factory(25)->create()->each(function ($category) use ($randArray) {

            $category->parent_id = $randArray[rand(0, count($randArray)-1)];
            $category->save();
        });
        
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}

