<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self public()
 * @method static self local()
 * @method static self s3()
 */
class DiskType extends Enum
{
    private const PUBLIC = 'public';
    private const LOCAL = 'local';
    private const S3 = 's3';

    protected static function values(): array
    {
        return [
            'public' => self::PUBLIC,
            'local' => self::LOCAL,
            's3' => self::S3,
        ];
    }
}
