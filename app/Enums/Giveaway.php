<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SINGLE_WINNER()
 * @method static static MULTIPLE_WINNERS()
 */
final class Giveaway extends Enum
{
    const SINGLE_WINNER = 0;

    /**
     * The number of winners is directly derived from
     * the number of pokemon in the giveaway
     */
    const MULTIPLE_WINNERS = 1;
}
