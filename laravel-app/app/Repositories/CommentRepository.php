<?php

namespace App\Repositories;

class CommentRepository implements CommentRepositoryInterface
{
    public function save($comment, $item)
    {
        return $item->comments()->save($comment);
    }
}
