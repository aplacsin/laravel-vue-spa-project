<?php

namespace App\Http\Controllers\API;

use App\DTO\PostDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\User;
use App\Policy\PostPolicy;
use App\Services\PostService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function list(): JsonResponse
    {
        $posts = $this->postService->getPostAll();

        return response()->json($posts, 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(int $id): JsonResponse
    {
        $this->authorize(PostPolicy::ACTION_EDIT, User::class);
        $post = $this->postService->getById($id);

        return response()->json($post, 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResponse
    {
        $this->authorize(PostPolicy::ACTION_SHOW, User::class);
        $post = $this->postService->getById($id);

        return response()->json($post, 200);
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
