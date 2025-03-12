<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        
        Category::factory()->count(10)->create();  

        
        $parentCategory = Category::create([
            'name' => 'Parent Category',
            'parent_id' => null
        ]);

        
        Category::factory()->count(5)->create([
            'parent_id' => $parentCategory->id
        ]);
    }
}

