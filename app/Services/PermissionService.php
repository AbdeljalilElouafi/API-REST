<?php

namespace App\Services;

use App\Repositories\Interfaces\PermissionRepositoryInterface;

class PermissionService {
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository) {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllPermissions() {
        return $this->permissionRepository->all();
    }

    public function getPermission($id) {
        return $this->permissionRepository->find($id);
    }

    public function createPermission(array $data) {
        return $this->permissionRepository->create($data);
    }

    public function updatePermission($id, array $data) {
        return $this->permissionRepository->update($id, $data);
    }

    public function deletePermission($id) {
        return $this->permissionRepository->delete($id);
    }
}