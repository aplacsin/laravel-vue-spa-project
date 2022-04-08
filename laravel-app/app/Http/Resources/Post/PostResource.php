<?php

declare(strict_types=1);

namespace App\Http\Resources\Post;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $guid
 * @property string $title
 * @property string $description
 * @property Carbon $created_at
 * @property mixed $comments
 */
class PostResource extends JsonResource
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
            'guid' => $this->guid,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'comments' => $this->comments,
        ];
    }
}
