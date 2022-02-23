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
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    public function setCreatedAtAttribute(string $value)
    {
        $this->attributes['created_at'] = (new Carbon($value))->format('Y-m-d H:i:s');
    }

    public function setUpdatedAtAttribute(string $value)
    {
        $this->attributes['updated_at'] = (new Carbon($value))->format('Y-m-d H:i:s');
    }
}
