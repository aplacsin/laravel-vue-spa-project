<?php

declare(strict_types=1);

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $totalPost
 * @property int $weekPost
 * @property int $totalVideo
 * @property int $weekVideo
 */
class PostStatisticResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'totalPost' => $this->totalPost,
            'weekPost' => $this->weekPost,
            'totalVideo' => $this->totalVideo,
            'weekVideo' => $this->weekVideo
        ];
    }
}
