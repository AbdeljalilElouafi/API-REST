<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class VideoController extends Controller
{
    public function store(Request $request, $courseId)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'video' => 'required|file|mimes:mp4,mov,avi|max:102400', 
    ]);

    $course = Course::findOrFail($courseId);

    if ($request->hasFile('video')) {
        $path = $request->file('video')->store('course_videos', 'public');

        $video = new Video([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $path,
            'course_id' => $course->id,
        ]);

        $course->videos()->save($video);

        return response()->json(['video' => $video], 201);
    }

    return response()->json(['message' => 'Video upload failed'], 400);
}
}
