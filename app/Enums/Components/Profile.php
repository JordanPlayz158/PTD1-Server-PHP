<?php declare(strict_types=1);

namespace App\Enums\Components;

use BenSampo\Enum\Enum;

/**
 * @method static static PRIMARY()
 * @method static static SECONDARY()
 * @method static static EXTENDED()
 */
final class Profile extends Enum
{
    public const PRIMARY = 0;
    public const SECONDARY = 1;
    public const EXTENDED = 2;
}
