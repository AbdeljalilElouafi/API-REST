<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Interfaces\TagRepositoryInterface;

class TagRepository implements TagRepositoryInterface {
    public function all() {
        return Tag::all();
    }

    public function find($id) {
        return Tag::findOrFail($id);
    }

    public function create(array $data) {
        return Tag::create($data);
    }

    public function update($id, array $data) {
        $tag = Tag::findOrFail($id);
        $tag->update($data);
        return $tag;
    }

    public function delete($id) {
        return Tag::destroy($id);
    }
}