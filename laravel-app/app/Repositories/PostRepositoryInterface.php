<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function save(Post $post): bool;

    public function findAllPost(): LengthAwarePaginator;

    public function findByGuId(string $id);

    public function findById(int $id);

    public function removeById(int $id): void;
}
