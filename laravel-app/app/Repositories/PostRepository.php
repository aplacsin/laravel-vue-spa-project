<?php

namespace App\Repositories;

use App\Filters\PostFilter;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PostRepository implements PostRepositoryInterface
{
    public function save(Post $post): bool
    {
        return $post->save();
    }

    public function list(PostFilter $search): LengthAwarePaginator
    {
        return Post::query()
            ->when($search->getSearch(), function (Builder $query, string $search): Builder {
                return $query->where('title', 'LIKE', '%'.$search.'%');
            })
            ->when($search->getStartDate(), function (Builder $query, string $date): Builder {
                return $query->whereDate('created_at', '>=', $date);
            })
            ->when($search->getEndDate(), function (Builder $query, string $date): Builder {
                return $query->whereDate('created_at','<=', $date);
            })
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
            ->with('comments.replies.user')
            ->first();
    }

    public function removeById(int $id): void
    {
        Post::query()
            ->findOrFail($id)
            ->delete();
    }
}
