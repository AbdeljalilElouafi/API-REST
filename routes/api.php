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


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
