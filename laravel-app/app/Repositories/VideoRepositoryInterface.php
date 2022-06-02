<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Video;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface VideoRepositoryInterface
{
    public function save(Video $video): Video;

    public function list(): LengthAwarePaginator;

    public function findByVideoId(int $videoId);

    public function findById(int $id);

    public function removeById(int $id): void;
}
