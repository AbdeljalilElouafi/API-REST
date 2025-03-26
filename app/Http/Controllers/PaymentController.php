<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/payments/checkout/{courseId}",
     *     summary="Initiate payment checkout",
     *     tags={"Payment"},
     *     @OA\Parameter(
     *         name="courseId",
     *         in="path",
     *         required=true,
     *         description="ID of the course to purchase",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"redirect_url"},
     *             @OA\Property(property="redirect_url", type="string", format="url", example="https://example.com/return")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Checkout initiated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="payment_url", type="string", example="https://checkout.stripe.com/pay/cs_test_abc123"),
     *             @OA\Property(property="redirect_url", type="string", example="https://example.com/return")
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
     *                     property="redirect_url",
     *                     type="array",
     *                     @OA\Items(type="string", example="The redirect url field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function checkout(Request $request, $courseId)
    {
        $request->validate([
            'redirect_url' => 'required|url',
        ]);

        $userId = Auth::id();
        $result = $this->paymentRepository->createCheckoutSession($courseId, $userId);

        return response()->json([
            'payment_url' => $result['payment_url'],
            'redirect_url' => $request->redirect_url,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/payments/success",
     *     summary="Handle successful payment",
     *     tags={"Payment"},
     *     @OA\Parameter(
     *         name="session_id",
     *         in="query",
     *         required=true,
     *         description="Payment session ID",
     *         @OA\Schema(type="string", example="cs_test_abc123")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment confirmed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Payment successful"),
     *             @OA\Property(property="session_id", type="string", example="cs_test_abc123")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        return response()->json([
            'message' => 'Payment successful',
            'session_id' => $sessionId,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/payments/cancel",
     *     summary="Handle canceled payment",
     *     tags={"Payment"},
     *     @OA\Response(
     *         response=400,
     *         description="Payment canceled",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Payment canceled")
     *         )
     *     )
     * )
     */
    public function paymentCancel(Request $request)
    {
        return response()->json([
            'message' => 'Payment canceled',
        ], 400);
    }

    /**
     * @OA\Get(
     *     path="/api/payments/status/{id}",
     *     summary="Get payment status",
     *     tags={"Payment"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Payment ID",
     *         @OA\Schema(type="string", example="pi_123456789")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment status",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", example="pi_123456789"),
     *             @OA\Property(property="amount", type="number", example=99.99),
     *             @OA\Property(property="currency", type="string", example="usd"),
     *             @OA\Property(property="status", type="string", example="succeeded"),
     *             @OA\Property(property="paid", type="boolean", example=true),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function paymentStatus($id)
    {
        $status = $this->paymentRepository->getPaymentStatus($id);
        return response()->json($status);
    }

    /**
     * @OA\Get(
     *     path="/api/payments/history",
     *     summary="Get user's payment history",
     *     tags={"Payment"},
     *     @OA\Response(
     *         response=200,
     *         description="Payment history",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="string", example="pi_123456789"),
     *                 @OA\Property(property="amount", type="number", example=99.99),
     *                 @OA\Property(property="currency", type="string", example="usd"),
     *                 @OA\Property(property="status", type="string", example="succeeded"),
     *                 @OA\Property(property="course_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function paymentHistory()
    {
        $userId = Auth::id();
        $history = $this->paymentRepository->getPaymentHistory($userId);
        return response()->json($history);
    }

    /**
     * @OA\Post(
     *     path="/api/payments/webhook",
     *     summary="Handle payment webhook",
     *     tags={"Payment"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Webhook payload",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Webhook processed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid webhook",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid webhook")
     *         )
     *     )
     * )
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        $result = $this->paymentRepository->handleWebhook($payload, $signature);

        return $result 
            ? response()->json(['success' => true])
            : response()->json(['error' => 'Invalid webhook'], 400);
    }
}