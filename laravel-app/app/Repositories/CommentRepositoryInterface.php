<?php

namespace App\Repositories;

interface CommentRepositoryInterface
{
    public function save($comment, $item);

    public function removeById(int $id, int $userId): void;
}
