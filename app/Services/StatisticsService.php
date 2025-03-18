<?php

namespace App\Services;

use App\Repositories\Interfaces\StatisticsRepositoryInterface;

class StatisticsService
{
    protected $statisticsRepository;

    public function __construct(StatisticsRepositoryInterface $statisticsRepository)
    {
        $this->statisticsRepository = $statisticsRepository;
    }

    public function getPlatformStatistics()
    {
        return $this->statisticsRepository->getPlatformStatistics();
    }
}