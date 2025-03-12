<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder {
    public function run() {
        
        Course::factory()->count(10)->create();
    }
}