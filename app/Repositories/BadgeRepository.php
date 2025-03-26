<?php

namespace App\Repositories;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\Enrollment;
use App\Models\Course;
use App\Repositories\Interfaces\BadgeRepositoryInterface;
use Carbon\Carbon;

class BadgeRepository implements BadgeRepositoryInterface
{
    public function getAllBadges()
    {
        return Badge::all();
    }

    public function getUserBadges($userId)
    {
        return UserBadge::with('badge')
            ->where('user_id', $userId)
            ->get();
    }

    public function createBadge(array $data)
    {
        return Badge::create($data);
    }

    public function updateBadge($id, array $data)
    {
        $badge = Badge::findOrFail($id);
        $badge->update($data);
        return $badge;
    }

    public function deleteBadge($id)
    {
        $badge = Badge::findOrFail($id);
        return $badge->delete();
    }

    public function checkAndAwardBadges($userId)
    {
        $user = User::with('enrollments')->findOrFail($userId);
        $badges = Badge::all();
        $awardedBadges = [];

        foreach ($badges as $badge) {
            if ($this->meetsConditions($user, $badge)) {
                $this->awardBadge($user, $badge);
                $awardedBadges[] = $badge;
            }
        }

        return $awardedBadges;
    }

    protected function meetsConditions(User $user, Badge $badge)
    {
        // Check if user already has this badge
        if ($user->badges()->where('badge_id', $badge->id)->exists()) {
            return false;
        }

        switch ($badge->type) {
            case 'course_completion':
                return $this->checkCourseCompletion($user, $badge);
            case 'mentor':
                return $this->checkMentorConditions($user, $badge);
            case 'activity':
                return $this->checkActivityConditions($user, $badge);
            default:
                return false;
        }
    }

    protected function checkCourseCompletion(User $user, Badge $badge)
    {
        $conditions = $badge->conditions ?? [];
        
        if (isset($conditions['courses_completed'])) {
            $completedCourses = $user->enrollments()
                ->where('progress', 100)
                ->count();
            
            return $completedCourses >= $conditions['courses_completed'];
        }
        
        return false;
    }

    protected function checkMentorConditions(User $user, Badge $badge)
    {
        if (!$user->hasRole('mentor')) return false;
        
        $conditions = $badge->conditions ?? [];
        $result = true;
        
        if (isset($conditions['courses_created'])) {
            $coursesCreated = Course::where('mentor_id', $user->id)->count();
            $result = $result && ($coursesCreated >= $conditions['courses_created']);
        }
        
        if (isset($conditions['students_enrolled'])) {
            $studentsEnrolled = Enrollment::whereHas('course', function($q) use ($user) {
                $q->where('mentor_id', $user->id);
            })->distinct('user_id')->count('user_id');
            
            $result = $result && ($studentsEnrolled >= $conditions['students_enrolled']);
        }
        
        if (isset($conditions['months_active'])) {
            $monthsActive = $user->created_at->diffInMonths(now());
            $result = $result && ($monthsActive >= $conditions['months_active']);
        }
        
        return $result;
    }

    protected function checkActivityConditions(User $user, Badge $badge)
    {
        $conditions = $badge->conditions ?? [];
        $result = true;
        
        if (isset($conditions['months_active'])) {
            $monthsActive = $user->created_at->diffInMonths(now());
            $result = $result && ($monthsActive >= $conditions['months_active']);
        }
        
        if (isset($conditions['profile_complete'])) {
            $profileComplete = !empty($user->bio) && !empty($user->profile_picture);
            $result = $result && $profileComplete;
        }
        
        return $result;
    }

    protected function awardBadge(User $user, Badge $badge)
    {
        UserBadge::create([
            'user_id' => $user->id,
            'badge_id' => $badge->id,
            'earned_at' => now(),
        ]);
    }
}