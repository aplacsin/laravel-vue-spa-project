<?php

namespace App\Http\Controllers\API;

use App\DTO\CommentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
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
            $request->get('comment_id')
        );

        $this->commentService->create($commentDTO);
    }
}
