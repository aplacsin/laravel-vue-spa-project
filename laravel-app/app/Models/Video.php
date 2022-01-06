<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $title
 * @property int $video_id
 * @property string $type
 */

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'video_id',
        'type'
    ];

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }
}
