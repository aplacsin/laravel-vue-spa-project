<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Video;
use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public function save($comment, $item)
    {
        return $item->comments()->save($comment);
    }

    public function findById(int $id)
    {
        return Comment::query()
            ->where('commentable_id', $id)
            ->where('commentable_id', $id)
            ->with('replies')
            ->with('user')
            ->get();
    }
}
