<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;





Route::apiResource('categories', CategoryController::class);
Route::get('/categories/{parentId}/subcategories', [CategoryController::class, 'getSubcategories']);
Route::middleware('auth:sanctum')->group(function () {
});



Route::apiResource('courses', CourseController::class);
Route::middleware('auth:sanctum')->group(function () {
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
