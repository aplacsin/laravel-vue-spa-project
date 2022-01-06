<?php

namespace App\Repositories;

interface CommentRepositoryInterface
{
    public function save($comment, $item);

    public function findByPostId(int $id);

    public function findByVideoId(int $id);
}
