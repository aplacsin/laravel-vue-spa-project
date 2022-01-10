<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{
    public function save(Post $post): bool
    {
        return $post->save();
    }

    public function findAllPost(): LengthAwarePaginator
    {
        return Post::query()
            ->paginate(15);
    }

    public function findByGuId($id)
    {
        return Post::query()
            ->where('guid', $id)
            ->first();
    }

    public function findById(int $id)
    {
        return Post::query()
            ->where('id', $id)
            ->with('comments')
            ->with('comments.user')
            ->with('comments.replies')
            ->first();
    }

    public function removeById(int $id): void
    {
        Post::query()
            ->findOrFail($id)
            ->delete();
    }

    public function searchByTitle(string $title)
    {
       return Post::query()
            ->where('title', 'LIKE', '%'.$title.'%')->get();
    }
}
