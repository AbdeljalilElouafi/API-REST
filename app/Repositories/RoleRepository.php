<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface {
    public function all() {
        return Role::all();
    }

    public function find($id) {
        return Role::findOrFail($id);
    }

    public function create(array $data) {
        return Role::create($data);
    }

    public function update($id, array $data) {
        $role = Role::findOrFail($id);
        $role->update($data);
        return $role;
    }

    public function delete($id) {
        return Role::destroy($id);
    }
}