<?php

namespace App\Repositories;

use App\Models\Video;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface VideoRepositoryInterface
{
    public function save(Video $video): bool;

    public function list(): LengthAwarePaginator;

    public function findByVideoId(int $id);

    public function findById(int $id);

    public function removeById(int $id): void;
}
