<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Exception;

class PermissionController extends Controller {
    protected $permissionService;

    public function __construct(PermissionService $permissionService) {
        $this->permissionService = $permissionService;
    }

    /**
     * @OA\Get(
     *     path="/api/permissions",
     *     summary="Get a list of permissions",
     *     tags={"Permission"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index() {
        try {
            $permissions = $this->permissionService->getAllPermissions();
            return response()->json(['permissions' => PermissionResource::collection($permissions)]);
        } catch (Exception $e) {
            \Log::error("Cannot get permissions: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to retrieve permissions"], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/permissions",
     *     summary="Store a new permission",
     *     tags={"Permission"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="create-courses")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Permission created"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function store(Request $request) {
        try {
            $permission = $this->permissionService->createPermission($request->all());
            return response()->json(['permission' => new PermissionResource($permission)], 201);
        } catch (Exception $e) {
            \Log::error("Cannot create permission: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to create permission"], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/permissions/{id}",
     *     summary="Get a permission by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Permission found"),
     *     @OA\Response(response=404, description="Permission not found")
     * )
     */
    public function show($id) {
        try {
            $permission = $this->permissionService->getPermission($id);
            return response()->json(['permission' => new PermissionResource($permission)]);
        } catch (Exception $e) {
            \Log::error("Cannot get permission: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Permission not found"], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/permissions/{id}",
     *     summary="Update a permission",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="edit-courses")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Permission updated"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function update(Request $request, $id) {
        try {
            $permission = $this->permissionService->updatePermission($id, $request->all());
            return response()->json(['permission' => new PermissionResource($permission)]);
        } catch (Exception $e) {
            \Log::error("Cannot update permission: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to update permission"], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/permissions/{id}",
     *     summary="Delete a permission",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Permission deleted successfully")
     * )
     */
    public function destroy($id) {
        try {
            $this->permissionService->deletePermission($id);
            return response()->noContent();
        } catch (Exception $e) {
            \Log::error("Cannot delete permission: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to delete permission"], 500);
        }
    }
}