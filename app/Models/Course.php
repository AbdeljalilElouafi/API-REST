<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'duration', 'difficulty_level', 'category_id', 'mentor_id', 'status',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function mentor() {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function videos() {
        return $this->hasMany(Video::class);
    }
}
