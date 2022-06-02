<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Comment;

interface CommentRepositoryInterface
{
    public function save(Comment $comment, object $item);

    public function update(Comment $comment): Comment;

    public function removeById(int $id, int $userId): void;

    public function findById(int $id);
}
