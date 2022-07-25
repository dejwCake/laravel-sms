<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Application;
use Matthewbdaly\LaravelSMS\Facade;
use Matthewbdaly\LaravelSMS\LaravelSMSProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  Application $app
     * @return array<int, class-string>
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    protected function getPackageProviders($app): array
    {
        return [LaravelSMSProvider::class];
    }

    /**
     * @param  Application $app
     * @return array<int, class-string>
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    protected function getPackageAliases($app): array
    {
        return [
            'SMS' => Facade::class,
        ];
    }
}
