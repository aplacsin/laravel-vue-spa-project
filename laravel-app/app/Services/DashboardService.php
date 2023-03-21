<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PostRepositoryInterface;
use App\Repositories\VideoRepositoryInterface;

class DashboardService
{
    private PostRepositoryInterface $postRepository;
    private VideoRepositoryInterface $videoRepository;

    public function __construct(
        PostRepositoryInterface  $postRepository,
        VideoRepositoryInterface $videoRepository
    )
    {
        $this->postRepository = $postRepository;
        $this->videoRepository = $videoRepository;
    }

    public function totalPost()
    {
        return $this->postRepository->totalPost();
    }

    public function weekPost()
    {
        return $this->postRepository->weekPost();
    }

    public function totalVideo()
    {
        return $this->videoRepository->totalVideo();
    }

    public function weekVideo()
    {
        return $this->videoRepository->weekVideo();
    }
}
