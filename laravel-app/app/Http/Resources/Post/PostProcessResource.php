<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property bool $processed
 * @property int $total
 * @property int current
 * @property string $file_name
 */
class PostProcessResource extends JsonResource
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
            'processed' => $this->processed,
            'total' => $this->total,
            'current' => $this->current,
            'file_name' => $this->file_name
        ];
    }
}
