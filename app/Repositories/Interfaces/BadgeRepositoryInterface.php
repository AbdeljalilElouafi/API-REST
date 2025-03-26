<?php

namespace App\Repositories\Interfaces;

interface BadgeRepositoryInterface
{
    public function getAllBadges();
    public function getUserBadges($userId);
    public function createBadge(array $data);
    public function updateBadge($id, array $data);
    public function deleteBadge($id);
    public function checkAndAwardBadges($userId);
}