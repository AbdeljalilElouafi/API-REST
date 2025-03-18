<?php

namespace App\Services;

use App\Repositories\Interfaces\MentorRepositoryInterface;

class MentorService
{
    protected $mentorRepository;

    public function __construct(MentorRepositoryInterface $mentorRepository)
    {
        $this->mentorRepository = $mentorRepository;
    }

    public function getMentorCourses($mentorId)
    {
        return $this->mentorRepository->getMentorCourses($mentorId);
    }

    public function getMentorStudents($mentorId)
    {
        return $this->mentorRepository->getMentorStudents($mentorId);
    }

    public function getMentorPerformance($mentorId)
    {
        return $this->mentorRepository->getMentorPerformance($mentorId);
    }
}