<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Video;

class CommentRepository implements CommentRepositoryInterface
{
    public function save($comment, $item)
    {
        return $item->comments()->save($comment);
    }

    public function findByPostId(int $id)
    {
        return Post::query()
            ->where('id', $id)
            ->first();
    }

    public function findByVideoId(int $id)
    {
        return Video::query()
            ->where('id', $id)
            ->first();
    }
}
