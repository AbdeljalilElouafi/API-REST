<?php

namespace App\Repositories\Interfaces;

interface StudentRepositoryInterface
{
    public function getStudentCourses($studentId);
    public function getStudentProgress($studentId);
    public function getStudentBadges($studentId);
}