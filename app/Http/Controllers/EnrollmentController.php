<?php

namespace App\Http\Controllers;

use App\Services\EnrollmentService;
use App\Http\Resources\EnrollmentResource;
use App\Models\Course;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller {
    protected $enrollmentService;
    protected $paymentRepository;

    public function __construct(
        EnrollmentService $enrollmentService,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->enrollmentService = $enrollmentService;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/enrollments",
     *     summary="Get a list of enrollments",
     *     tags={"Enrollment"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index() {
        try {
            $enrollments = $this->enrollmentService->getAllEnrollments();
            return response()->json(['enrollments' => EnrollmentResource::collection($enrollments)]);
        } catch (Exception $e) {
            \Log::error("Cannot get enrollments: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to retrieve enrollments"], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/enrollments",
     *     summary="Store a new enrollment",
     *     tags={"Enrollment"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "course_id", "status"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="course_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="pending")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Enrollment created"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function store(Request $request) {
        try {
            $enrollment = $this->enrollmentService->createEnrollment($request->all());
            return response()->json(['enrollment' => new EnrollmentResource($enrollment)], 201);
        } catch (Exception $e) {
            \Log::error("Cannot create enrollment: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to create enrollment"], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/enrollments/{courseId}/enroll",
     *     summary="Enroll in a course with payment flow",
     *     tags={"Enrollment"},
     *     @OA\Parameter(
     *         name="courseId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Enrollment successful or payment required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="enrollment", type="boolean"),
     *             @OA\Property(property="payment_required", type="boolean"),
     *             @OA\Property(property="payment_url", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Course not found")
     * )
     */
    public function enroll(Request $request, $courseId) {
        try {
            $course = Course::findOrFail($courseId);
            $user = Auth::user();

            // Check if course is free
            if ($course->price <= 0) {
                // Direct enrollment for free courses
                $enrollment = $this->enrollmentService->createEnrollment([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'status' => 'active',
                ]);

                return response()->json([
                    'message' => 'Enrolled successfully',
                    'enrollment' => new EnrollmentResource($enrollment),
                    'payment_required' => false,
                ]);
            }

            // For paid courses, initiate payment
            $result = $this->paymentRepository->createCheckoutSession($courseId, $user->id);

            return response()->json([
                'message' => 'Payment required for enrollment',
                'enrollment' => false,
                'payment_required' => true,
                'payment_url' => $result['payment_url'],
            ]);
        } catch (Exception $e) {
            \Log::error("Enrollment error: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Enrollment failed"], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/enrollments/{id}",
     *     summary="Get an enrollment by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Enrollment found"),
     *     @OA\Response(response=404, description="Enrollment not found")
     * )
     */
    public function show($id) {
        try {
            $enrollment = $this->enrollmentService->getEnrollment($id);
            return response()->json(['enrollment' => new EnrollmentResource($enrollment)]);
        } catch (Exception $e) {
            \Log::error("Cannot get enrollment: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Enrollment not found"], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/enrollments/{id}",
     *     summary="Update an enrollment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "course_id", "status"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="course_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="active")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Enrollment updated"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function update(Request $request, $id) {
        try {
            $enrollment = $this->enrollmentService->updateEnrollment($id, $request->all());
            return response()->json(['enrollment' => new EnrollmentResource($enrollment)]);
        } catch (Exception $e) {
            \Log::error("Cannot update enrollment: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to update enrollment"], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/enrollments/{id}",
     *     summary="Delete an enrollment",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Enrollment deleted successfully")
     * )
     */
    public function destroy($id) {
        try {
            $this->enrollmentService->deleteEnrollment($id);
            return response()->noContent();
        } catch (Exception $e) {
            \Log::error("Cannot delete enrollment: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to delete enrollment"], 500);
        }
    }
}