<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;
use Exception;

class CourseController extends Controller {
    protected $courseService;

    public function __construct(CourseService $courseService) {
        $this->courseService = $courseService;
    }

    public function index() {
        try {
            $courses = $this->courseService->getAllCourses();
            return response()->json(['courses' => CourseResource::collection($courses)]);
        } catch (Exception $e) {
            \Log::error("Cannot get courses: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to retrieve courses"], 500);
        }
    }

    public function store(Request $request) {
        try {
            $course = $this->courseService->createCourse($request->all());
            return response()->json(['course' => new CourseResource($course)], 201);
        } catch (Exception $e) {
            \Log::error("Cannot create course: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to create course"], 500);
        }
    }

    public function show($id) {
        try {
            $course = $this->courseService->getCourse($id);
            return response()->json(['course' => new CourseResource($course)]);
        } catch (Exception $e) {
            \Log::error("Cannot get course: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Course not found"], 404);
        }
    }

    public function update(Request $request, $id) {
        try {
            $course = $this->courseService->updateCourse($id, $request->all());
            return response()->json(['course' => new CourseResource($course)]);
        } catch (Exception $e) {
            \Log::error("Cannot update course: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to update course"], 500);
        }
    }

    public function destroy($id) {
        try {
            $this->courseService->deleteCourse($id);
            return response()->noContent();
        } catch (Exception $e) {
            \Log::error("Cannot delete course: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to delete course"], 500);
        }
    }
}