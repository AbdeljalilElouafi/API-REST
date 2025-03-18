<?php

namespace App\Http\Controllers;

use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * @OA\Get(
     *     path="/api/V1/students/{id}/courses",
     *     summary="List courses enrolled by a student",
     *     tags={"Student"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Courses retrieved successfully"),
     *     @OA\Response(response=404, description="Student not found")
     * )
     */
    public function getCourses($id)
    {
        $courses = $this->studentService->getStudentCourses($id);
        return response()->json(['courses' => $courses]);
    }

    /**
     * @OA\Get(
     *     path="/api/V1/students/{id}/progress",
     *     summary="Track a student's progress in their courses",
     *     tags={"Student"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Progress retrieved successfully"),
     *     @OA\Response(response=404, description="Student not found")
     * )
     */
    public function getProgress($id)
    {
        $progress = $this->studentService->getStudentProgress($id);
        return response()->json(['progress' => $progress]);
    }

    /**
     * @OA\Get(
     *     path="/api/V1/students/{id}/badges",
     *     summary="List badges earned by a student",
     *     tags={"Student"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Badges retrieved successfully"),
     *     @OA\Response(response=404, description="Student not found")
     * )
     */
    public function getBadges($id)
    {
        $badges = $this->studentService->getStudentBadges($id);
        return response()->json(['badges' => $badges]);
    }

}