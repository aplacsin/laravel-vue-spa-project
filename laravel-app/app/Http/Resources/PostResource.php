<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'guid' => $this->guid,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'current_page' => $this->current_page,
            'total' => $this->total
        ];
    }
}
