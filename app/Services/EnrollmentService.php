<?php

namespace App\Services;

use App\Repositories\Interfaces\EnrollmentRepositoryInterface;

class EnrollmentService {
    protected $enrollmentRepository;

    public function __construct(EnrollmentRepositoryInterface $enrollmentRepository) {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    public function getAllEnrollments() {
        return $this->enrollmentRepository->all();
    }

    public function getEnrollment($id) {
        return $this->enrollmentRepository->find($id);
    }

    public function createEnrollment(array $data) {
        return $this->enrollmentRepository->create($data);
    }

    public function updateEnrollment($id, array $data) {
        return $this->enrollmentRepository->update($id, $data);
    }

    public function deleteEnrollment($id) {
        return $this->enrollmentRepository->delete($id);
    }

    public function getEnrollmentsByUser($userId) {
        return $this->enrollmentRepository->getEnrollmentsByUser($userId);
    }

    public function getEnrollmentsByCourse($courseId) {
        return $this->enrollmentRepository->getEnrollmentsByCourse($courseId);
    }
}