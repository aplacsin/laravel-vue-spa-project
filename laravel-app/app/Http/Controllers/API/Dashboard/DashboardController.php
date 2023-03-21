<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Resources\Post\PostStatisticResource;
use App\Services\DashboardService;

class DashboardController
{
    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): PostStatisticResource
    {
        $totalPost = $this->dashboardService->totalPost();
        $weekPost = $this->dashboardService->weekPost();

        $totalVideo = $this->dashboardService->totalVideo();
        $weekVideo = $this->dashboardService->weekVideo();

        $result = array_merge((array)$totalPost, (array)$weekPost, (array)$totalVideo, (array)$weekVideo);
        $object = json_decode(json_encode($result), false);

        return new PostStatisticResource($object);
    }
}
