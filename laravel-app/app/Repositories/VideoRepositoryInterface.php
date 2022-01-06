<?php

namespace App\Repositories;

use App\Models\Video;

interface VideoRepositoryInterface
{
    public function save(Video $video): bool;

    public function findAllVideo();

    public function findByVideoId(int $id);

    public function findById(int $id);

    public function removeById(int $id);
}
