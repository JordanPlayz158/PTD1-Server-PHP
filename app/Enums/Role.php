<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ADMIN()
 * @method static static USER()
 */
final class Role extends Enum
{
    private const ADMIN = 2;
    private const USER = 1;
}
