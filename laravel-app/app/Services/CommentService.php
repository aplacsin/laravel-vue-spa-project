<?php

namespace App\Services;

use App\DTO\CommentDTO;
use App\Enums\CommentType;
use App\Models\Comment;
use App\Repositories\CommentRepositoryInterface;
use Illuminate\Support\Facades\Auth;

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
        if ($commentDTO->getParentId()) {
            $comment->parent_id = $commentDTO->getParentId();
        }

        $this->typeComment($commentDTO, $comment);
    }

    /**
     * @param CommentDTO $commentDTO
     * @param Comment $comment
     * @return void
     */
    public function typeComment(CommentDTO $commentDTO, Comment $comment): void
    {
        $type = $commentDTO->getType();

        if ($type === CommentType::post()->value) {
            $post = $this->postService->getById($commentDTO->getId());
            $this->commentRepository->save($comment, $post);
        }

        if ($type === CommentType::video()->value) {
            $video = $this->videoService->getById($commentDTO->getId());
            $this->commentRepository->save($comment, $video);
        }
    }

    public function delete(int $id): void
    {
        $userId = Auth::id();
        $this->commentRepository->removeById($id, $userId);
    }
}
