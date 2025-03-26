<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Repositories\Interfaces\StatisticsRepositoryInterface;

class StatisticsRepository implements StatisticsRepositoryInterface
{
    public function getPlatformStatistics()
    {
        return [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
        ];
    }
}