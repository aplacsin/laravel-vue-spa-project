<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Filters\PostFilter;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PostRepository implements PostRepositoryInterface
{
    const PER_PAGE = 15;

    public function save(Post $post): Post
    {
        $post->save();

        return $post;
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
            ->orderBy('id', 'DESC')
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
            ->findOrFail($id);
    }

    public function removeById(int $id): void
    {
        Post::query()
            ->findOrFail($id)
            ->delete();
    }

    public function rawListByKeyId(array $id)
    {
        return Post::query()
            ->whereKey($id)
            ->get();
    }

    public function listPosts(?int $offset, ?int $limit, ?array $ids): array
    {
        $query = DB::table('posts');
        $this->buildCollection($query);

        if ($ids) {
            $query->whereIn('posts.id', $ids);
        }

        return $query
            ->limit($limit)
            ->offset($offset)
            ->orderBy('id')
            ->get()
            ->toArray();
    }

    private function buildCollection($builder)
    {
        $builder->select('posts.id as id', 'posts.guid as guid', 'posts.title as title', 'posts.description as description');
    }

    public function totalPost()
    {
        return DB::table('posts')
            ->select(DB::raw('COUNT(*) as totalPost'))
            ->first();
    }

    public function weekPost()
    {
        $data = Carbon::today()->subDays(7);

        return DB::table('posts')
            ->select(DB::raw('COUNT(*) as weekPost'))
            ->where('created_at', '>=', $data)
            ->first();
    }
}
