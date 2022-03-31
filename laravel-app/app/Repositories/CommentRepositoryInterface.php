<?php

namespace App\Repositories;

interface CommentRepositoryInterface
{
    public function save($comment, $item);

    public function update($comment): bool;

    public function removeById(int $id, int $userId): void;

    public function findById(int $id);
}
