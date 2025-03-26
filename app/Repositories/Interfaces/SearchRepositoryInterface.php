<?php

namespace App\Repositories\Interfaces;

interface SearchRepositoryInterface
{
    public function searchCourses(string $query = null, int $categoryId = null, string $difficulty = null);
    public function searchMentors(string $query = null);
    public function filterStudentsByBadges(array $badgeIds = null);
}