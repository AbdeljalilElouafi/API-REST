<?php

namespace App\Repositories\Interfaces;

interface MentorRepositoryInterface
{
    public function getMentorCourses($mentorId);
    public function getMentorStudents($mentorId);
    public function getMentorPerformance($mentorId);
}