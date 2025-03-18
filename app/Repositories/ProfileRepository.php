<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function getProfile($userId)
    {
        return User::findOrFail($userId);
    }

    public function updateProfile($userId, array $data)
    {
        $user = User::findOrFail($userId);
        $user->update($data);
        return $user;
    }
}