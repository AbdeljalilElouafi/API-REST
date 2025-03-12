<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Exception;

class TagController extends Controller {
    protected $tagService;

    public function __construct(TagService $tagService) {
        $this->tagService = $tagService;
    }

    public function index() {
        try {
            $tags = $this->tagService->getAllTags();
            return response()->json(['tags' => TagResource::collection($tags)]);
        } catch (Exception $e) {
            \Log::error("Cannot get tags: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to retrieve tags"], 500);
        }
    }

    public function store(Request $request) {
        try {
            $tag = $this->tagService->createTag($request->all());
            return response()->json(['tag' => new TagResource($tag)], 201);
        } catch (Exception $e) {
            \Log::error("Cannot create tag: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to create tag"], 500);
        }
    }

    public function show($id) {
        try {
            $tag = $this->tagService->getTag($id);
            return response()->json(['tag' => new TagResource($tag)]);
        } catch (Exception $e) {
            \Log::error("Cannot get tag: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Tag not found"], 404);
        }
    }

    public function update(Request $request, $id) {
        try {
            $tag = $this->tagService->updateTag($id, $request->all());
            return response()->json(['tag' => new TagResource($tag)]);
        } catch (Exception $e) {
            \Log::error("Cannot update tag: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to update tag"], 500);
        }
    }

    public function destroy($id) {
        try {
            $this->tagService->deleteTag($id);
            return response()->noContent();
        } catch (Exception $e) {
            \Log::error("Cannot delete tag: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to delete tag"], 500);
        }
    }
}
