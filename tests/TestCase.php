<?php

declare(strict_types=1);

namespace DejwCake\LaravelSms\Tests;

use DejwCake\LaravelSms\Facades\Sms;
use DejwCake\LaravelSms\LaravelSmsProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  Application $app
     * @return array<int|string, class-string>
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    protected function getPackageProviders($app): array
    {
        return [LaravelSmsProvider::class];
    }

    /**
     * @param  Application $app
     * @return array<int|string, class-string>
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Sms' => Sms::class,
        ];
    }
}
