<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripePaymentRepository implements PaymentRepositoryInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession($courseId, $userId)
    {
        $course = Course::findOrFail($courseId);
        $user = User::findOrFail($userId);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $course->name,
                    ],
                    'unit_amount' => $course->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => config('app.url') . '/api/payment/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => config('app.url') . '/api/payment/cancel',
            'metadata' => [
                'course_id' => $course->id,
                'user_id' => $user->id,
            ],
        ]);

        return [
            'session_id' => $session->id,
            'payment_url' => $session->url,
        ];
    }

    public function getPaymentStatus($paymentIntentId)
    {
        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
        
        return [
            'status' => $paymentIntent->status,
            'amount' => $paymentIntent->amount,
            'currency' => $paymentIntent->currency,
            'created' => $paymentIntent->created,
        ];
    }

    public function getPaymentHistory($userId)
    {
        return Payment::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function handleWebhook($payload, $signature)
    {
        try {
            $event = Webhook::constructEvent(
                $payload, $signature, config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return false;
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handlePaymentSuccess($session);
                break;
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->recordSuccessfulPayment($paymentIntent);
                break;
        }

        return true;
    }

    protected function handlePaymentSuccess($session)
    {
        $courseId = $session->metadata->course_id;
        $userId = $session->metadata->user_id;

        // Enroll the student
        Enrollment::create([
            'course_id' => $courseId,
            'user_id' => $userId,
            'status' => 'active',
        ]);

        // Record payment
        Payment::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'amount' => $session->amount_total / 100,
            'currency' => $session->currency,
            'payment_method' => 'stripe',
            'transaction_id' => $session->payment_intent,
            'status' => 'completed',
        ]);
    }

    protected function recordSuccessfulPayment($paymentIntent)
    {
        Payment::where('transaction_id', $paymentIntent->id)
            ->update(['status' => 'completed']);
    }
}