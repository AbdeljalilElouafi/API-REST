<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface {
    public function all() {
        return Category::all();
    }

    public function find($id) {
        return Category::findOrFail($id);
    }

    public function create(array $data) {
        return Category::create($data);
    }

    public function update($id, array $data) {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete($id) {
        return Category::destroy($id);
    }

    public function getSubcategories($parentId) {
        return Category::where('parent_id', $parentId)->get();
    }
}