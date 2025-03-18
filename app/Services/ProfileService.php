<?php

namespace App\Services;

use App\Repositories\Interfaces\ProfileRepositoryInterface;

class ProfileService
{
    protected $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function getProfile($userId)
    {
        return $this->profileRepository->getProfile($userId);
    }

    public function updateProfile($userId, array $data)
    {
        return $this->profileRepository->updateProfile($userId, $data);
    }
}