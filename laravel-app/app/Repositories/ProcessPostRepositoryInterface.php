<?php

namespace App\Repositories;

use App\Models\ProcessPost;

interface ProcessPostRepositoryInterface
{
    public function findById(int $id);

    public function save(ProcessPost $complete): bool;
}
