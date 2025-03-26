<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Mentor;
use App\Models\Student;
use App\Models\UserBadge;
use App\Repositories\Interfaces\SearchRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchRepository implements SearchRepositoryInterface
{
    public function searchCourses(string $query = null, int $categoryId = null, string $difficulty = null)
    {
        $courses = Course::query()
            ->with(['category', 'mentor'])
            ->when($query, function ($q) use ($query) {
                $q->where(function($queryBuilder) use ($query) {
                    $queryBuilder->where('title', 'like', "%{$query}%")
                                ->orWhere('description', 'like', "%{$query}%");
                });
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when($difficulty, function ($q) use ($difficulty) {
                $q->where('difficulty_level', $difficulty);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.pagination_per_page'));

        return $courses;
    }

    public function searchMentors(string $query = null)
    {
        $mentors = Mentor::query()
            ->when($query, function ($q) use ($query) {
                $q->where(function($queryBuilder) use ($query) {
                    $queryBuilder->where('name', 'like', "%{$query}%")
                                ->orWhere('expertise', 'like', "%{$query}%");
                });
            })
            ->withCount('courses')
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.pagination_per_page'));

        return $mentors;
    }

    public function filterStudentsByBadges(array $badgeIds = null)
    {
        $students = Student::query()
            ->when($badgeIds, function ($q) use ($badgeIds) {
                $q->whereHas('badges', function($query) use ($badgeIds) {
                    $query->whereIn('badges.id', $badgeIds);
                });
            })
            ->with(['badges'])
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.pagination_per_page'));

        return $students;
    }
}