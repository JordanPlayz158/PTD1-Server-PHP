<?php declare(strict_types=1);

namespace App\Enums\Components;

use BenSampo\Enum\Enum;

/**
 * @method static static OFFER()
 * @method static static REQUEST()
 * @method static static NONE()
 */
final class Trading extends Enum
{
    public const OFFER = 0;
    public const REQUEST = 1;
    public const NONE = 2;
}
