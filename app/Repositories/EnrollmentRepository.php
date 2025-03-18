<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;

class EnrollmentRepository implements EnrollmentRepositoryInterface {
    public function all() {
        return Enrollment::all();
    }

    public function find($id) {
        return Enrollment::findOrFail($id);
    }

    public function create(array $data) {
        return Enrollment::create($data);
    }

    public function update($id, array $data) {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update($data);
        return $enrollment;
    }

    public function delete($id) {
        return Enrollment::destroy($id);
    }

    public function getEnrollmentsByUser($userId) {
        return Enrollment::where('user_id', $userId)->get();
    }

    public function getEnrollmentsByCourse($courseId) {
        return Enrollment::where('course_id', $courseId)->get();
    }
}