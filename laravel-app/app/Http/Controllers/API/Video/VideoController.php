<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Video;

use App\Http\Controllers\Controller;
use App\Http\Resources\Video\VideoCollection;
use App\Http\Resources\Video\VideoResource;
use App\Services\VideoService;

class VideoController extends Controller
{
    private VideoService $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function list(): VideoCollection
    {
        $videos = $this->videoService->getVideo();

        return new VideoCollection($videos);
    }

    public function show(int $id): VideoResource
    {
        $video = $this->videoService->getById($id);

        return new VideoResource($video);
    }
}
