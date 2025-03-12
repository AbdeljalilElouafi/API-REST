<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory {
    protected $model = Course::class;

    public function definition() {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'duration' => $this->faker->numberBetween(30, 180), 
            'difficulty_level' => $this->faker->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'category_id' => $this->faker->numberBetween(1, 5), 
            'mentor_id' => $this->faker->numberBetween(1, 1), 
        ];
    }
}
