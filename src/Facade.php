<?php

declare(strict_types=1);

namespace Matthewbdaly\LaravelSMS;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * Facade for the SMS provider
 */
final class Facade extends BaseFacade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'sms';
    }
}
