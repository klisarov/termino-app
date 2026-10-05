<?php

namespace App\Exceptions;

use Exception;

/**
 * Thrown when a requested booking slot can no longer be honoured - most
 * often because someone else booked it a moment earlier.
 */
class SlotUnavailableException extends Exception
{
    protected $message = 'That time slot is no longer available. Please choose another time.';
}
