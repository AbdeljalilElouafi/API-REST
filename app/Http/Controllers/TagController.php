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

    /**
     * @OA\Get(
     *     path="/api/tags",
     *     summary="Get all tags",
     *     @OA\Response(
     *         response=200,
     *         description="A list of tags",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TagResource")
     *         )
     *     )
     * )
     */
    public function index() {
        try {
            $tags = $this->tagService->getAllTags();
            return response()->json(['tags' => TagResource::collection($tags)]);
        } catch (Exception $e) {
            \Log::error("Cannot get tags: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to retrieve tags"], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/tags",
     *     summary="Create a new tag",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TagRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TagResource")
     *     )
     * )
     */
    public function store(Request $request) {
        try {
            $tag = $this->tagService->createTag($request->all());
            return response()->json(['tag' => new TagResource($tag)], 201);
        } catch (Exception $e) {
            \Log::error("Cannot create tag: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to create tag"], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tags/{id}",
     *     summary="Get a tag by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A single tag",
     *         @OA\JsonContent(ref="#/components/schemas/TagResource")
     *     )
     * )
     */
    public function show($id) {
        try {
            $tag = $this->tagService->getTag($id);
            return response()->json(['tag' => new TagResource($tag)]);
        } catch (Exception $e) {
            \Log::error("Cannot get tag: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Tag not found"], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/tags/{id}",
     *     summary="Update a tag",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TagRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TagResource")
     *     )
     * )
     */
    public function update(Request $request, $id) {
        try {
            $tag = $this->tagService->updateTag($id, $request->all());
            return response()->json(['tag' => new TagResource($tag)]);
        } catch (Exception $e) {
            \Log::error("Cannot update tag: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to update tag"], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/tags/{id}",
     *     summary="Delete a tag",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Tag deleted successfully"
     *     )
     * )
     */
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