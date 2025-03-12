<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Exception;

class CategoryController extends Controller {
    protected $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     @OA\Response(
     *         response=200,
     *         description="A list of categories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CategoryResource")
     *         )
     *     )
     * )
     */
    public function index() {
        try {
            $categories = $this->categoryService->getAllCategories();
            return response()->json(['categories' => CategoryResource::collection($categories)]);
        } catch (Exception $e) {
            \Log::error("Cannot get categories: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to retrieve categories"], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Create a new category",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResource")
     *     )
     * )
     */
    public function store(Request $request) {
        try {
            $category = $this->categoryService->createCategory($request->all());
            return response()->json(['category' => new CategoryResource($category)], 201);
        } catch (Exception $e) {
            \Log::error("Cannot create category: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to create category"], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Get a category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A single category",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResource")
     *     )
     * )
     */
    public function show($id) {
        try {
            $category = $this->categoryService->getCategory($id);
            return response()->json(['category' => new CategoryResource($category)]);
        } catch (Exception $e) {
            \Log::error("Cannot get category: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Category not found"], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Update a category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResource")
     *     )
     * )
     */
    public function update(Request $request, $id) {
        try {
            $category = $this->categoryService->updateCategory($id, $request->all());
            return response()->json(['category' => new CategoryResource($category)]);
        } catch (Exception $e) {
            \Log::error("Cannot update category: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to update category"], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Delete a category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category deleted successfully"
     *     )
     * )
     */
    public function destroy($id) {
        try {
            $this->categoryService->deleteCategory($id);
            return response()->noContent();
        } catch (Exception $e) {
            \Log::error("Cannot delete category: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to delete category"], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{parentId}/subcategories",
     *     summary="Get subcategories for a parent category",
     *     @OA\Parameter(
     *         name="parentId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of subcategories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CategoryResource")
     *         )
     *     )
     * )
     */
    public function getSubcategories($parentId) {
        try {
            $subcategories = $this->categoryService->getSubcategories($parentId);
            return response()->json(['subcategories' => CategoryResource::collection($subcategories)]);
        } catch (Exception $e) {
            \Log::error("Cannot get subcategories: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to retrieve subcategories"], 500);
        }
    }
}