<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder {
    public function run() {
        // Create 5 tags
        Tag::factory()->count(5)->create();
    }
}
