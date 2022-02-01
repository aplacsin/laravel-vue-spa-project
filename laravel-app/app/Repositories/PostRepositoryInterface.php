<?php

namespace App\Repositories;

use App\Models\Post;
use App\Services\Filters\PostFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function save(Post $post): bool;

    public function list(PostFilter $filter): LengthAwarePaginator;

    public function findByGuId(string $id);

    public function findById(int $id);

    public function removeById(int $id): void;
}
