<?php

declare(strict_types=1);

namespace App\Http\Resources\Video;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $guid
 * @property string $title
 * @property int $video_id
 * @property string $type
 * @property Carbon $created_at
 * @property mixed $comments
 */
class VideoResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'video_id' => $this->video_id,
            'created_at' => $this->created_at,
            'comments' => $this->comments,
        ];
    }
}
