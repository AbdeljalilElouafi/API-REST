<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SearchController;





Route::get('/badges', [BadgeController::class, 'index']);
Route::get('/students/{user}/badges', [BadgeController::class, 'getUserBadges']);
Route::post('/badges/check', [BadgeController::class, 'checkBadges'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/badges', [BadgeController::class, 'store']);
    Route::put('/badges/{badge}', [BadgeController::class, 'update']);
    Route::delete('/badges/{badge}', [BadgeController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/payments/checkout/{course}', [PaymentController::class, 'checkout']);
    Route::get('/payments/status/{id}', [PaymentController::class, 'paymentStatus']);
    Route::get('/payments/history', [PaymentController::class, 'paymentHistory']);
});


Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook']);


Route::apiResource('categories', CategoryController::class);
Route::get('/categories/{parentId}/subcategories', [CategoryController::class, 'getSubcategories']);
Route::middleware('auth:sanctum')->group(function () {
});


Route::middleware('auth:sanctum')->group(function () {
   
    Route::get('/{id}/courses', [MentorController::class, 'getCourses']);
    Route::get('/{id}/students', [MentorController::class, 'getStudents']);
    Route::get('/{id}/performance', [MentorController::class, 'getPerformance']);
    
});


Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/{id}/courses', [StudentController::class, 'getCourses']);
    Route::get('/{id}/progress', [StudentController::class, 'getProgress']);
    Route::get('/{id}/badges', [StudentController::class, 'getBadges']);
    
});


Route::apiResource('roles', RoleController::class);
Route::middleware('auth:sanctum')->group(function () {
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/statistics', [StatisticsController::class, 'index'])->middleware('role:admin');
});

Route::apiResource('permissions', PermissionController::class);
Route::middleware('auth:sanctum')->group(function () {
});

Route::apiResource('enrollments', EnrollmentController::class);
Route::middleware('auth:sanctum')->group(function () {
});

Route::apiResource('courses', CourseController::class);
Route::apiResource('tags', TagController::class);
Route::middleware('auth:sanctum')->group(function () {
});

Route::get('/courses', [SearchController::class, 'searchCourses']);
Route::get('/mentors', [SearchController::class, 'searchMentors']);
Route::get('/students', [SearchController::class, 'filterStudentsByBadges']);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
