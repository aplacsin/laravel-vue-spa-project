<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(StoreCommentRequest $request): void
    {
        $this->commentService->create($request);
        
    }

    public function replyStore(StoreCommentRequest $request): void
    {
        $this->commentService->reply($request);
        
    }
}
