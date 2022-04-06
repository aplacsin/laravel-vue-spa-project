<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $title
 * @property string $description
 * @property string $guid
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'guid',
        'created_at'
    ];

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id')
            ->with('user')
            ->with('replies.user');
    }

    public function getCreatedAtAttribute(string $date): string
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    public function getUpdatedAtAttribute(string $date): string
    {
        return Carbon::parse($date)->format('d/m/Y');
    }
}
