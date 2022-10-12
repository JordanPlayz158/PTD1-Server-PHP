<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static DATABASE_CONNECTION()
 * @method static static OLD_VERSION()
 * @method static static NOT_FOUND()
 * @method static static TAKEN()
 * @method static static MAINTENANCE()
 * @method static static LOGGED_IN()
 * @method static static INVALID_ACTION()
 * @method static static Success()
 * @method static static Failure()
 */
final class Reason extends Enum
{
    // Result = Failure
    // Valid Reason Responses
    private const DATABASE_CONNECTION = 'DatabaseConnection';
    private const OLD_VERSION = 'oldVersion';
    private const NOT_FOUND = 'NotFound';
    private const TAKEN = 'taken';
    private const MAINTENANCE = 'maintenance';

    // Result = Success
    // Valid Reason Response (Yes, Singular)
    private const LOGGED_IN = 'LoggedIn';


    // Custom defined ones not recognized by SWF
    private const INVALID_ACTION = 'InvalidAction';
}
