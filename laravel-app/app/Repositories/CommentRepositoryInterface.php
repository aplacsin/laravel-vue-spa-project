<?php

namespace App\Repositories;

interface CommentRepositoryInterface
{
    public function save($comment, $item);

    public function findById(int $id);
}
