<?php

namespace App\Repositories\Interfaces;

interface PaymentRepositoryInterface
{
    public function createCheckoutSession($courseId, $userId);
    public function getPaymentStatus($paymentIntentId);
    public function getPaymentHistory($userId);
    public function handleWebhook($payload, $signature);
}