<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * @OA\Get(
     *     path="/api/statistics",
     *     summary="Get platform statistics (Admin only)",
     *     tags={"Statistics"},
     *     @OA\Response(response=200, description="Statistics retrieved successfully"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index()
    {
        // Ensure the user is an admin
        if (!Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $statistics = $this->statisticsService->getPlatformStatistics();
        return response()->json($statistics);
    }
}