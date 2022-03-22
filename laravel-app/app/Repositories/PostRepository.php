<?php

namespace App\Repositories;

use App\Filters\PostFilter;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PostRepository implements PostRepositoryInterface
{
    const PER_PAGE = 14;

    public function save(Post $post): bool
    {
        return $post->save();
    }

    public function list(PostFilter $filter): LengthAwarePaginator
    {
        return Post::query()
            ->when($filter->getSearch(), function (Builder $query, string $search): Builder {
                return $query->where('title', 'LIKE', '%' . $search . '%');
            })
            ->when($filter->getStartDate(), function (Builder $query, string $date): Builder {
                return $query->whereDate('created_at', '>=', $date);
            })
            ->when($filter->getEndDate(), function (Builder $query, string $date): Builder {
                return $query->whereDate('created_at', '<=', $date);
            })
            ->when($filter->getSortField() && $filter->getSortDirection(), function (Builder $query) use ($filter): Builder {
                return $query->orderBy($filter->getSortField(), $filter->getSortDirection());
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(self::PER_PAGE);
    }

    public function findByGuId(string $guid)
    {
        return Post::query()
            ->where('guid', $guid)
            ->first();
    }

    public function findById(int $id)
    {
        return Post::query()
            ->where('id', $id)
            ->with('comments')
            ->with('comments.user')
            ->first();
    }

    public function removeById(int $id): void
    {
        Post::query()
            ->findOrFail($id)
            ->delete();
    }
}
