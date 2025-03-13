<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Exception;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CategoryResource",
 *     type="object",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Category Name"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null)
 * )
 */

/**
 * @OA\Schema(
 *     schema="CategoryRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Category Name"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null)
 * )
 */
class CategoryController extends Controller {
    protected $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

     /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get a list of categories",
     *     tags={"Category"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
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
     *     summary="Store a new category",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Technology")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Category created"),
     *     @OA\Response(response=400, description="Invalid request")
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
     *     summary="Get category details",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Category not found")
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
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Science")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Category updated"),
     *     @OA\Response(response=400, description="Invalid request")
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
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
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
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="parentId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Science")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Category updated"),
     *     @OA\Response(response=400, description="Invalid request")
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