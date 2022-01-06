<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $title
 * @property string $description
 * @property string $guid
 * @property string $type
 */

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'guid',
        'type'
    ];

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }
}
