<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Repositories\Interfaces\MentorRepositoryInterface;

class MentorRepository implements MentorRepositoryInterface
{
    public function getMentorCourses($mentorId)
    {
        return Course::where('mentor_id', $mentorId)->get();
    }

    public function getMentorStudents($mentorId)
    {
        return Enrollment::whereHas('course', function ($query) use ($mentorId) {
            $query->where('mentor_id', $mentorId);
        })->with('user')->get()->pluck('user')->unique();
    }

    public function getMentorPerformance($mentorId)
    {
        $totalCourses = Course::where('mentor_id', $mentorId)->count();
        $totalStudents = $this->getMentorStudents($mentorId)->count();
        $completedCourses = Course::where('mentor_id', $mentorId)
            ->whereHas('enrollments', function ($query) {
                $query->where('status', 'completed');
            })->count();

        return [
            'total_courses' => $totalCourses,
            'total_students' => $totalStudents,
            'completed_courses' => $completedCourses,
        ];
    }
}