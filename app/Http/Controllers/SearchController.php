<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;
use Exception;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * @OA\Get(
     *     path="/api/V3/courses",
     *     summary="Search and filter courses",
     *     tags={"Search"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="difficulty",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="difficulty_level", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function searchCourses(Request $request)
    {
        try {
            $courses = $this->searchService->searchCourses($request->all());
            return response()->json($courses);
        } catch (Exception $e) {
            \Log::error("Search courses failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search courses'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/V3/mentors",
     *     summary="Search mentors",
     *     tags={"Search"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="expertise", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function searchMentors(Request $request)
    {
        try {
            $mentors = $this->searchService->searchMentors($request->all());
            return response()->json($mentors);
        } catch (Exception $e) {
            \Log::error("Search mentors failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search mentors'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/V3/students",
     *     summary="Filter students by badges",
     *     tags={"Search"},
     *     @OA\Parameter(
     *         name="badges",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(
     *                     property="badges",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function filterStudentsByBadges(Request $request)
    {
        try {
            $students = $this->searchService->filterStudentsByBadges($request->all());
            return response()->json($students);
        } catch (Exception $e) {
            \Log::error("Filter students by badges failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to filter students'
            ], 500);
        }
    }
}