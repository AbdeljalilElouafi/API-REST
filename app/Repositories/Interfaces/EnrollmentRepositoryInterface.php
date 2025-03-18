<?php

namespace App\Repositories\Interfaces;

interface EnrollmentRepositoryInterface {
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getEnrollmentsByUser($userId);
    public function getEnrollmentsByCourse($courseId);
}