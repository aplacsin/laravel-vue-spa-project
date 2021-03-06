<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Video;
use App\Repositories\VideoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VideoService
{
    private VideoRepositoryInterface $videoRepository;

    public function __construct(VideoRepositoryInterface $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function create(array $item): void
    {
        $videoId = explode('/', $item['uri']);

        $video = new Video();
        $video->title = $item['name'];
        $video->video_id = $videoId[2];

        $this->videoRepository->save($video);
    }

    public function getVideo(): LengthAwarePaginator
    {
        return $this->videoRepository->list();
    }

    public function getById(int $id)
    {
        return $this->videoRepository->findById($id);
    }
}
