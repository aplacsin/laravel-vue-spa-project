<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Comment;

use App\DTO\CommentDTO;
use App\DTO\CommentUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Services\CommentService;

class CommentController extends Controller
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(StoreCommentRequest $request): void
    {
        $commentDTO = CommentDTO::make(
            $request->user()->id,
            $request->input('id'),
            $request->input('content'),
            $request->input('type'),
            $request->input('parentId')
        );

        $this->commentService->create($commentDTO);
    }

    public function destroy(int $id): void
    {
        $this->commentService->delete($id);
    }

    public function edit(int $id): CommentResource
    {
        $comment = $this->commentService->getById($id);

        return new CommentResource($comment);
    }

    public function update(StoreCommentRequest $request, int $id): void
    {
        $commentDTO = CommentUpdateDTO::make(
            $request->input('content')
        );

        $this->commentService->update($commentDTO, $id);
    }
}
