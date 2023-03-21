<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Filters\PostFilter;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function save(Post $post): Post;

    public function list(PostFilter $filter): LengthAwarePaginator;

    public function findByGuId(string $guid);

    public function findById(int $id);

    public function removeById(int $id): void;

    public function rawListByKeyId(array $id);

    public function listPosts(?int $offset, ?int $limit, ?array $ids): array;

    public function totalPost();

    public function weekPost();
}
