<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ProcessPost;

class ProcessPostRepository implements ProcessPostRepositoryInterface
{
    public function findById(int $id)
    {
        return ProcessPost::query()
            ->where('id', $id)
            ->first();
    }

    public function save(ProcessPost $complete): bool
    {
        return $complete->save();
    }
}
