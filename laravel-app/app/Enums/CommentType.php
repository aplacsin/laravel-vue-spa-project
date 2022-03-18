<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self post()
 * @method static self video()
 */
class CommentType extends Enum
{
    private const POST = 'post';
    private const VIDEO = 'video';

    protected static function values(): array
    {
        return [
            'post' => self::POST,
            'video' => self::VIDEO,
        ];
    }
}
