<?php declare(strict_types=1);

namespace App\Enums\Components\Pokemon;

use BenSampo\Enum\Enum;

/**
 * @method static static NONE()
 * @method static static STANDARD()
 * @method static static TRADE()
 */
final class Actions extends Enum
{
    public const NONE = 0;
    public const STANDARD = 1;
    public const TRADE = 2;
}
