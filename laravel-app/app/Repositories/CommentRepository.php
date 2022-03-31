<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public function save($comment, $item)
    {
        return $item->comments()->save($comment);
    }

    public function removeById(int $id, int $userId): void
    {
        $comment = Comment::query()
            ->where([
                ['id', $id],
                ['user_id', $userId]
            ])
            ->first();

        if (!is_null($comment)) {
            $comment->delete();
        }
    }

    public function findById(int $id)
    {
        return Comment::query()
            ->findOrFail($id);
    }

    public function update($comment): bool
    {
        return $comment->save();
    }
}
