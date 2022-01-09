<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\VideoService;
use Illuminate\Http\JsonResponse;

class VideoController extends Controller
{
    private VideoService $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function list(): JsonResponse
    {
        $videos = $this->videoService->getVideoAll();

        return response()->json($videos, 200);
    }

    public function show(int $id): JsonResponse
    {
        $video = $this->videoService->getById($id);

        return response()->json($video, 200);
    }
}
