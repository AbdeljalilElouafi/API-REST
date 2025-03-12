<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller {
    protected $courseService;

    public function __construct(CourseService $courseService) {
        $this->courseService = $courseService;
    }

    public function index() {
        return $this->courseService->getAllCourses();
    }

    public function store(Request $request) {
        return $this->courseService->createCourse($request->all());
    }

    public function show($id) {
        return $this->courseService->getCourse($id);
    }

    public function update(Request $request, $id) {
        return $this->courseService->updateCourse($id, $request->all());
    }

    public function destroy($id) {
        return $this->courseService->deleteCourse($id);
    }
}
