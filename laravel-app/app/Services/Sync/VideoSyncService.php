<?php

declare(strict_types=1);

namespace App\Services\Sync;

use App\Repositories\VideoRepositoryInterface;
use App\Services\VideoService;

class VideoSyncService
{
    private VideoRepositoryInterface $videoRepository;
    private VideoService $videoService;

    public function __construct(
        VideoRepositoryInterface $videoRepository,
        VideoService             $videoService
    )
    {
        $this->videoRepository = $videoRepository;
        $this->videoService = $videoService;
    }

    public function sync(array $videos): void
    {
        foreach ($videos['body']['data'] as $video) {
            $this->videoSync($video);
        }
    }

    public function videoSync(array $video): void
    {
        $videoId = explode('/', $video['uri']);
        $modelVideo = $this->videoRepository->findByVideoId((int)$videoId[2]);

        if ($modelVideo == null) {
            $this->videoService->create($video);
        }
    }
}
