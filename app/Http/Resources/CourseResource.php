<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "duration" => $this->duration,
            "difficulty_level" => $this->difficulty_level,
            "status" => $this->status,
            "category_id" => $this->category_id,
            "mentor_id" => $this->mentor_id,
        ];
    }
}