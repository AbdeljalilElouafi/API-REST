<?php

namespace App\Services;

use App\Repositories\Interfaces\SearchRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    protected $searchRepository;

    public function __construct(SearchRepositoryInterface $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function searchCourses(array $params): LengthAwarePaginator
    {
        return $this->searchRepository->searchCourses(
            $params['search'] ?? null,
            $params['category'] ?? null,
            $params['difficulty'] ?? null
        );
    }

    public function searchMentors(array $params): LengthAwarePaginator
    {
        return $this->searchRepository->searchMentors(
            $params['search'] ?? null
        );
    }

    public function filterStudentsByBadges(array $params): LengthAwarePaginator
    {
        $badgeIds = isset($params['badges']) ? explode(',', $params['badges']) : null;
        return $this->searchRepository->filterStudentsByBadges($badgeIds);
    }
}