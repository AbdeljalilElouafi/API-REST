<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Models\Badge;
use App\Repositories\Interfaces\StudentRepositoryInterface;

class StudentRepository implements StudentRepositoryInterface
{
    public function getStudentCourses($studentId)
    {
        return Enrollment::where('user_id', $studentId)->with('course')->get()->pluck('course');
    }

    public function getStudentProgress($studentId)
    {
        return Enrollment::where('user_id', $studentId)
            ->with('course')
            ->get()
            ->map(function ($enrollment) {
                return [
                    'course' => $enrollment->course,
                    'progress' => $enrollment->progress, // Assuming you have a `progress` column in the `enrollments` table
                ];
            });
    }

    public function getStudentBadges($studentId)
    {
        return Badge::where('user_id', $studentId)->get();
    }
}