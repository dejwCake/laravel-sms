<?php

declare(strict_types=1);

namespace DejwCake\LaravelSms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for the SMS provider
 */
final class Sms extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'sms';
    }
}
