<?php

namespace App\Http\Controllers\API;

use App\DTO\PostDTO;
use App\Filters\PostFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostListRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostResource;
use App\Models\User;
use App\Policy\PostPolicy;
use App\Services\PostService;
use Illuminate\Auth\Access\AuthorizationException;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function list(PostListRequest $request): PostCollection
    {
        $postFilter = PostFilter::make(
            $request->get('page'),
            $request->get('search'),
            $request->get('startDate'),
            $request->get('endDate'),
            $request->get('sortField'),
            $request->get('sortDirection')
        );

        $posts = $this->postService->getPosts($postFilter);

        return new PostCollection($posts);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(int $id): PostResource
    {
        $this->authorize(PostPolicy::ACTION_EDIT, User::class);
        $post = $this->postService->getById($id);

        return new PostResource($post);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(int $id): PostResource
    {
        $this->authorize(PostPolicy::ACTION_SHOW, User::class);
        $post = $this->postService->getById($id);

        return new PostResource($post);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(int $id): void
    {
        $this->authorize(PostPolicy::ACTION_DELETE, User::class);
        $this->postService->delete($id);
    }

    public function update(StorePostRequest $request, int $id): void
    {
        $postDTO = PostDTO::make(
            $request->input('title'),
            $request->input('description')
        );

        $this->postService->update($postDTO, $id);
    }
}
