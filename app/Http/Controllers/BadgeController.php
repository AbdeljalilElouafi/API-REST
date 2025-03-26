<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BadgeRepositoryInterface;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    protected $badgeRepository;

    public function __construct(BadgeRepositoryInterface $badgeRepository)
    {
        $this->badgeRepository = $badgeRepository;
        
        $this->middleware('auth:sanctum')->except(['index']);
        $this->middleware('role:admin')->only(['store', 'update', 'destroy']);
    }

    /**
     * @OA\Get(
     *     path="/api/badges",
     *     summary="Get all badges",
     *     tags={"Badge"},
     *     @OA\Response(
     *         response=200,
     *         description="List of badges",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Course Master"),
     *                 @OA\Property(property="description", type="string", example="Completed 5 courses"),
     *                 @OA\Property(property="image_url", type="string", example="https://example.com/badge.png"),
     *                 @OA\Property(property="type", type="string", enum={"course_completion", "mentor", "activity"}),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $badges = $this->badgeRepository->getAllBadges();
        return response()->json($badges);
    }

    /**
     * @OA\Get(
     *     path="/api/badges/user/{userId}",
     *     summary="Get user's badges",
     *     tags={"Badge"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of user's badges",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Course Master"),
     *                 @OA\Property(property="description", type="string", example="Completed 5 courses"),
     *                 @OA\Property(property="image_url", type="string", example="https://example.com/badge.png"),
     *                 @OA\Property(property="earned_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function getUserBadges($userId)
    {
        $badges = $this->badgeRepository->getUserBadges($userId);
        return response()->json($badges);
    }

    /**
     * @OA\Post(
     *     path="/api/badges",
     *     summary="Create a new badge",
     *     tags={"Badge"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "image_url", "type"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Course Master"),
     *             @OA\Property(property="description", type="string", example="Completed 5 courses"),
     *             @OA\Property(property="image_url", type="string", format="url", example="https://example.com/badge.png"),
     *             @OA\Property(property="type", type="string", enum={"course_completion", "mentor", "activity"}, example="course_completion"),
     *             @OA\Property(property="conditions", type="object", example={"courses_completed": 5})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Badge created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Course Master"),
     *             @OA\Property(property="description", type="string", example="Completed 5 courses"),
     *             @OA\Property(property="image_url", type="string", example="https://example.com/badge.png"),
     *             @OA\Property(property="type", type="string", example="course_completion"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'required|url',
            'type' => 'required|in:course_completion,mentor,activity',
            'conditions' => 'nullable|json',
        ]);

        $badge = $this->badgeRepository->createBadge($validated);
        return response()->json($badge, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/badges/{id}",
     *     summary="Update a badge",
     *     tags={"Badge"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Badge ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="Updated Badge Name"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="image_url", type="string", format="url", example="https://example.com/new-badge.png"),
     *             @OA\Property(property="type", type="string", enum={"course_completion", "mentor", "activity"}),
     *             @OA\Property(property="conditions", type="object", example={"courses_completed": 10})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Badge updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated Badge Name"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="image_url", type="string", example="https://example.com/new-badge.png"),
     *             @OA\Property(property="type", type="string", example="course_completion"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Badge not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Badge not found")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image_url' => 'sometimes|url',
            'type' => 'sometimes|in:course_completion,mentor,activity',
            'conditions' => 'nullable|json',
        ]);

        $badge = $this->badgeRepository->updateBadge($id, $validated);
        return response()->json($badge);
    }

    /**
     * @OA\Delete(
     *     path="/api/badges/{id}",
     *     summary="Delete a badge",
     *     tags={"Badge"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Badge ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Badge deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Badge not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Badge not found")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy($id)
    {
        $this->badgeRepository->deleteBadge($id);
        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/api/badges/check",
     *     summary="Check and award badges for user",
     *     tags={"Badge"},
     *     @OA\Response(
     *         response=200,
     *         description="List of awarded badges",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Course Master"),
     *                 @OA\Property(property="description", type="string", example="Completed 5 courses"),
     *                 @OA\Property(property="earned_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function checkBadges(Request $request)
    {
        $userId = $request->user()->id;
        $awardedBadges = $this->badgeRepository->checkAndAwardBadges($userId);
        return response()->json($awardedBadges);
    }
}