<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

// Test listing categories
test("can list categories", function () {
    Category::factory()->count(3)->create();

    $response = $this->get("api/categories");
    $response->assertStatus(200);
    $response->assertJsonStructure([
        "categories" => [
            "*" => [
                'name',
            ],
        ],
    ]);
});

// Test adding a category
test("can add a category", function () {
    $category = [
        "name" => "Technology"
    ];

    $response = $this->post("api/categories", $category);
    $response->assertStatus(201);
    $this->assertDatabaseHas('categories', ['name' => $category['name']]);
});

// Test user registration
test("can register a user", function () {
    $userData = [
        "name" => "John Doe",
        "email" => "john@example.com",
        "password" => "password",
        "password_confirmation" => "password",
    ];

    $response = $this->post("api/register", $userData);
    $response->assertStatus(200);
    $this->assertDatabaseHas('users', ['email' => $userData['email']]);
});

// Test user login
test("can login a user", function () {
    $user = User::factory()->create([
        "email" => "john@example.com",
        "password" => bcrypt("password"),
    ]);

    $loginData = [
        "email" => "john@example.com",
        "password" => "password",
    ];

    $response = $this->post("api/login", $loginData);
    $response->assertStatus(200);
    $response->assertJsonStructure(['token']);
});

// Test creating a course (protected route)
test("can create a course", function () {
    $category = \App\Models\Category::factory()->create();
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $courseData = [
        "name" => "Introduction to Laravel",
        "description" => "Learn Laravel from scratch",
        "duration" => 10,
        "difficulty_level" => "beginner",
        "category_id" => $category->id,
    ];

    $response = $this->post("api/courses", $courseData);
    $response->assertStatus(201);
    $this->assertDatabaseHas('courses', ['name' => $courseData['name']]);
});

// Test updating a user profile (protected route)
test("can update user profile", function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileData = [
        "name" => "Updated Name",
        "email" => "updated@example.com",
    ];

    $response = $this->put("api/profile", $profileData);
    $response->assertStatus(200);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => $profileData['name'],
        'email' => $profileData['email'],
    ]);
});

// Test enrolling in a course (protected route)
test("can enroll in a course", function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $course = Course::factory()->create();

    $enrollmentData = [
        "course_id" => $course->id,
    ];

    $response = $this->post("api/courses/{$course->id}/enroll", $enrollmentData);
    $response->assertStatus(200);
    $this->assertDatabaseHas('enrollments', [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
});

// Test token refresh
test("can refresh token", function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->post("api/refresh");
    $response->assertStatus(200);
    $response->assertJsonStructure(['token']);
});

// Test uploading a profile image
test("can upload profile image", function () {
    Storage::fake('public'); // Fake the storage disk

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $file = UploadedFile::fake()->image('profile.jpg');

    $response = $this->put("api/profile", [
        'profile_image' => $file,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'profile_image' => 'profile_images/' . $file->hashName(),
    ]);

    Storage::disk('public')->assertExists('profile_images/' . $file->hashName());
});