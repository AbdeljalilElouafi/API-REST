<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Exception;

class RoleController extends Controller {
    protected $roleService;

    public function __construct(RoleService $roleService) {
        $this->roleService = $roleService;
    }

    /**
     * @OA\Get(
     *     path="/api/roles",
     *     summary="Get a list of roles",
     *     tags={"Role"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index() {
        try {
            $roles = $this->roleService->getAllRoles();
            return response()->json(['roles' => RoleResource::collection($roles)]);
        } catch (Exception $e) {
            \Log::error("Cannot get roles: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to retrieve roles"], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     summary="Store a new role",
     *     tags={"Role"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Admin")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Role created"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function store(Request $request) {
        try {
            $role = $this->roleService->createRole($request->all());
            return response()->json(['role' => new RoleResource($role)], 201);
        } catch (Exception $e) {
            \Log::error("Cannot create role: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to create role"], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     summary="Get a role by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Role found"),
     *     @OA\Response(response=404, description="Role not found")
     * )
     */
    public function show($id) {
        try {
            $role = $this->roleService->getRole($id);
            return response()->json(['role' => new RoleResource($role)]);
        } catch (Exception $e) {
            \Log::error("Cannot get role: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Role not found"], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     summary="Update a role",
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
     *             @OA\Property(property="name", type="string", example="Mentor")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Role updated"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function update(Request $request, $id) {
        try {
            $role = $this->roleService->updateRole($id, $request->all());
            return response()->json(['role' => new RoleResource($role)]);
        } catch (Exception $e) {
            \Log::error("Cannot update role: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to update role"], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     summary="Delete a role",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Role deleted successfully")
     * )
     */
    public function destroy($id) {
        try {
            $this->roleService->deleteRole($id);
            return response()->noContent();
        } catch (Exception $e) {
            \Log::error("Cannot delete role: " . $e->getMessage());
            return response()->json(["success" => false, "message" => "Failed to delete role"], 500);
        }
    }
}