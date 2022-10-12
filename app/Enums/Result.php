<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SUCCESS()
 * @method static static FAILURE()
 */
final class Result extends Enum
{
    private const SUCCESS = 'Success';
    private const FAILURE = 'Failure';
}
