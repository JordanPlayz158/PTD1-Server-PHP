<?php declare(strict_types=1);

namespace App\Enums\Components\Offers;

use BenSampo\Enum\Enum;

/**
 * @method static static ACCEPT()
 * @method static static DENY()
 * @method static static RETRACT()
 */
final class Actions extends Enum
{
    public const ACCEPT = 0;
    public const DENY = 1;
    public const RETRACT = 2;
}
