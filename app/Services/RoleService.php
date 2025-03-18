<?php

namespace App\Services;

use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleService {
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository) {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles() {
        return $this->roleRepository->all();
    }

    public function getRole($id) {
        return $this->roleRepository->find($id);
    }

    public function createRole(array $data) {
        return $this->roleRepository->create($data);
    }

    public function updateRole($id, array $data) {
        return $this->roleRepository->update($id, $data);
    }

    public function deleteRole($id) {
        return $this->roleRepository->delete($id);
    }
}