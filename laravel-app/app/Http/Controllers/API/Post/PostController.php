<?php

namespace App\Http\Controllers\API\Post;

use App\DTO\PostDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditPostRequest;
use App\Http\Requests\PostListRequest;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostResource;
use App\Services\Filters\PostFilter;
use App\Services\PostService;


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
            $request->input('page'),
            $request->input('search'),
            $request->input('startDate'),
            $request->input('endDate'),
            $request->input('sortField'),
            $request->input('sortDirection')
        );

        $posts = $this->postService->getPosts($postFilter);

        return new PostCollection($posts);
    }

    public function edit(int $id): PostResource
    {
        $post = $this->postService->getById($id);

        return new PostResource($post);
    }

    public function show(int $id): PostResource
    {
        $post = $this->postService->getById($id);

        return new PostResource($post);
    }

    public function destroy(int $id): void
    {
        $this->postService->delete($id);
    }

    public function update(EditPostRequest $request, int $id): void
    {
        $postDTO = PostDTO::make(
            $request->input('title'),
            $request->input('description')
        );

        $this->postService->update($postDTO, $id);
    }
}
