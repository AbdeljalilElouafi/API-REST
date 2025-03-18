<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;

class PermissionRepository implements PermissionRepositoryInterface {
    public function all() {
        return Permission::all();
    }

    public function find($id) {
        return Permission::findOrFail($id);
    }

    public function create(array $data) {
        return Permission::create($data);
    }

    public function update($id, array $data) {
        $permission = Permission::findOrFail($id);
        $permission->update($data);
        return $permission;
    }

    public function delete($id) {
        return Permission::destroy($id);
    }
}