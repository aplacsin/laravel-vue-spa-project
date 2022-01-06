<?php

namespace App\Services;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Repositories\CommentRepositoryInterface;

class CommentService
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function create(StoreCommentRequest $request): void
    {
        $comment = new Comment;
        $comment->content = $request->input('content');
        $comment->user()->associate($request->user());

        $this->extracted($request, $comment);
    }

    public function reply(StoreCommentRequest $request): void
    {
        $reply = new Comment;
        $reply->content = $request->input('content');
        $reply->user()->associate($request->user());
        $reply->parent_id = $request->get('comment_id');

        $this->extracted($request, $reply);
    }

    /**
     * @param StoreCommentRequest $request
     * @param Comment $comment
     * @return void
     */
    public function extracted(StoreCommentRequest $request, Comment $comment): void
    {
        $type = $request->input('type');

        if ($type == 'post') {
            $post = $this->commentRepository->findByPostId($request->input('id'));
            $this->commentRepository->save($comment, $post);
        }

        if ($type == 'video') {
            $video = $this->commentRepository->findByVideoId($request->input('id'));
            $this->commentRepository->save($comment, $video);
        }
    }
}
