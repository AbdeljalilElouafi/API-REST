<?php

namespace App\Services;

use App\Repositories\Interfaces\StudentRepositoryInterface;

class StudentService
{
    protected $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function getStudentCourses($studentId)
    {
        return $this->studentRepository->getStudentCourses($studentId);
    }

    public function getStudentProgress($studentId)
    {
        return $this->studentRepository->getStudentProgress($studentId);
    }

    public function getStudentBadges($studentId)
    {
        return $this->studentRepository->getStudentBadges($studentId);
    }
}