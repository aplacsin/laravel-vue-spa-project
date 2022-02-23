<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $total
 * @property int $current
 * @property bool $processed
 * @property string $file_name
 */
class ProcessPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'current',
        'processed',
        'file_name'
    ];
}
