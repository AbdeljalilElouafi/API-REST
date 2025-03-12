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

    /**
     * @OA\Get(
     *     path="/api/courses",
     *     summary="Get all courses",
     *     @OA\Response(
     *         response=200,
     *         description="A list of courses",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CourseResource")
     *         )
     *     )
     * )
     */
    public function index() {
        try {
            $courses = $this->courseService->getAllCourses();
            return response()->json(['courses' => CourseResource::collection($courses)]);
        } catch (Exception $e) {
            \Log::error("Cannot get courses: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to retrieve courses"], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/courses",
     *     summary="Create a new course",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CourseRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Course created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CourseResource")
     *     )
     * )
     */
    public function store(Request $request) {
        try {
            $course = $this->courseService->createCourse($request->all());
            return response()->json(['course' => new CourseResource($course)], 201);
        } catch (Exception $e) {
            \Log::error("Cannot create course: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to create course"], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/courses/{id}",
     *     summary="Get a course by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A single course",
     *         @OA\JsonContent(ref="#/components/schemas/CourseResource")
     *     )
     * )
     */
    public function show($id) {
        try {
            $course = $this->courseService->getCourse($id);
            return response()->json(['course' => new CourseResource($course)]);
        } catch (Exception $e) {
            \Log::error("Cannot get course: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Course not found"], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/courses/{id}",
     *     summary="Update a course",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CourseRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CourseResource")
     *     )
     * )
     */
    public function update(Request $request, $id) {
        try {
            $course = $this->courseService->updateCourse($id, $request->all());
            return response()->json(['course' => new CourseResource($course)]);
        } catch (Exception $e) {
            \Log::error("Cannot update course: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to update course"], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/courses/{id}",
     *     summary="Delete a course",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Course deleted successfully"
     *     )
     * )
     */
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