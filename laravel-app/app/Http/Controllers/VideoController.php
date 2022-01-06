<?php

namespace App\Http\Controllers;

use App\Services\VideoService;

class VideoController extends Controller
{
    private VideoService $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function index()
    {
        $videos = $this->videoService->getVideoAll();

        return response()->json($videos, 200);
    }

    public function show(int $id)
    {
        $video = $this->videoService->getById($id);

        return response()->json($video, 200);
    }
}
