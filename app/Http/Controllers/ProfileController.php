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
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $user = User::findOrFail($id);
    
        if ($request->hasFile('profile_image')) {
            
            if ($user->profile_image) {
                Storage::delete($user->profile_image);
            }
    
           
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }
    
        $user->update($request->except('profile_image'));
    
        return response()->json(['user' => $user], 200);
    }
}