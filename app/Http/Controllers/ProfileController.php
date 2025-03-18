<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Get the authenticated user's profile",
     *     tags={"Profile"},
     *     @OA\Response(response=200, description="Profile retrieved successfully"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show()
    {
        $userId = Auth::id();
        $profile = $this->profileService->getProfile($userId);
        return response()->json(['profile' => $profile]);
    }

    /**
     * @OA\Put(
     *     path="/api/profile",
     *     summary="Update the authenticated user's profile",
     *     tags={"Profile"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="bio", type="string", example="A passionate mentor/student."),
     *             @OA\Property(property="avatar_url", type="string", example="https://example.com/avatar.jpg")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Profile updated successfully"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'bio' => 'nullable|string',
            'avatar_url' => 'nullable|url',
        ]);

        $profile = $this->profileService->updateProfile($userId, $request->all());
        return response()->json(['message' => 'Profile updated successfully', 'profile' => $profile]);
    }
}