<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
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
