<?php


namespace App\Swagger;

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
