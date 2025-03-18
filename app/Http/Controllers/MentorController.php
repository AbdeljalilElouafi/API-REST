<?php

namespace App\Http\Controllers;

use App\Services\MentorService;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    protected $mentorService;

    public function __construct(MentorService $mentorService)
    {
        $this->mentorService = $mentorService;
    }

    /**
     * @OA\Get(
     *     path="/api/V1/mentors/{id}/courses",
     *     summary="List courses created by a mentor",
     *     tags={"Mentor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Courses retrieved successfully"),
     *     @OA\Response(response=404, description="Mentor not found")
     * )
     */
    public function getCourses($id)
    {
        $courses = $this->mentorService->getMentorCourses($id);
        return response()->json(['courses' => $courses]);
    }

    /**
     * @OA\Get(
     *     path="/api/V1/mentors/{id}/students",
     *     summary="List students enrolled in a mentor's courses",
     *     tags={"Mentor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Students retrieved successfully"),
     *     @OA\Response(response=404, description="Mentor not found")
     * )
     */
    public function getStudents($id)
    {
        $students = $this->mentorService->getMentorStudents($id);
        return response()->json(['students' => $students]);
    }

    /**
     * @OA\Get(
     *     path="/api/V1/mentors/{id}/performance",
     *     summary="Get performance statistics of a mentor",
     *     tags={"Mentor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Performance statistics retrieved successfully"),
     *     @OA\Response(response=404, description="Mentor not found")
     * )
     */
    public function getPerformance($id)
    {
        $performance = $this->mentorService->getMentorPerformance($id);
        return response()->json(['performance' => $performance]);
    }
}