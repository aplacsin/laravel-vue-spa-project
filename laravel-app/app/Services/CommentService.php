<?php

namespace App\Services;

use App\DTO\CommentDTO;
use App\Models\Comment;
use App\Repositories\CommentRepositoryInterface;

class CommentService
{
    private CommentRepositoryInterface $commentRepository;
    private PostService $postService;
    private VideoService $videoService;

    public function __construct(CommentRepositoryInterface $commentRepository, PostService $postService, VideoService $videoService)
    {
        $this->commentRepository = $commentRepository;
        $this->postService = $postService;
        $this->videoService = $videoService;
    }

    public function create(CommentDTO $commentDTO): void
    {
        $comment = new Comment;
        $comment->content = $commentDTO->getContent();
        $comment->user()->associate($commentDTO->getUserId());

        $this->extracted($commentDTO, $comment);
    }

    public function reply(CommentDTO $commentDTO): void
    {
        $reply = new Comment;
        $reply->content = $commentDTO->getContent();
        $reply->user()->associate($commentDTO->getUserId());
        $reply->parent_id = $commentDTO->getParentId();

        $this->extracted($commentDTO, $reply);
    }

    /**
     * @param CommentDTO $commentDTO
     * @param Comment $comment
     * @return void
     */
    public function extracted(CommentDTO $commentDTO, Comment $comment): void
    {
        $type = $commentDTO->getType();

        if ($type == 'post') {
            $post = $this->postService->getById($commentDTO->getId());
            $this->commentRepository->save($comment, $post);
        }

        if ($type == 'video') {
            $video = $this->videoService->getById($commentDTO->getId());
            $this->commentRepository->save($comment, $video);
        }
    }
}
