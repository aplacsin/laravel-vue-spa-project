<?php

namespace App\Repositories;

use App\Models\Video;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VideoRepository implements VideoRepositoryInterface
{
    public function save(Video $video): bool
    {
        if ($video == null) {
            $video->exists = true;
        }

        return $video->save();
    }

    public function findByVideoId(int $id)
    {
        return Video::query()
            ->where('video_id', $id)
            ->first();
    }

    public function findById(int $id)
    {
        return Video::query()
            ->where('id', $id)
            ->with('comments')
            ->with('comments.user')
            ->with('comments.replies')
            ->first();
    }

    public function findAllVideo(): LengthAwarePaginator
    {
        return Video::query()
            ->paginate(10);
    }

    public function removeById(int $id)
    {
        // TODO: Implement removeById() method.
    }
}
