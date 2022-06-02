<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Video;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VideoRepository implements VideoRepositoryInterface
{
    const PER_PAGE = 10;

    public function save(Video $video): Video
    {
        $video->save();

        return $video;
    }

    public function list(): LengthAwarePaginator
    {
        return Video::query()
            ->paginate(self::PER_PAGE);
    }

    public function findByVideoId(int $videoId)
    {
        return Video::query()
            ->where('video_id', $videoId)
            ->first();
    }

    public function findById(int $id)
    {
        return Video::query()
            ->where('id', $id)
            ->with('comments')
            ->with('comments.user')
            ->first();
    }

    public function removeById(int $id): void
    {
        Video::query()
            ->findOrFail($id)
            ->delete();
    }
}
