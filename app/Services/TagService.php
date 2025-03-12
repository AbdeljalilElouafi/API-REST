<?php

namespace App\Services;

use App\Repositories\Interfaces\TagRepositoryInterface;

class TagService {
    protected $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository) {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTags() {
        return $this->tagRepository->all();
    }

    public function getTag($id) {
        return $this->tagRepository->find($id);
    }

    public function createTag(array $data) {
        return $this->tagRepository->create($data);
    }

    public function updateTag($id, array $data) {
        return $this->tagRepository->update($id, $data);
    }

    public function deleteTag($id) {
        return $this->tagRepository->delete($id);
    }
}