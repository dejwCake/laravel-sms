<?php

declare(strict_types=1);

namespace DejwCake\LaravelSms;

use DejwCake\LaravelSms\Mailers\MailAdapter;
use DejwCake\SmsClient\Client;
use DejwCake\SmsClient\Contracts\Client as SmsClient;
use DejwCake\SmsClient\Contracts\Driver;
use DejwCake\SmsClient\Drivers\Aws;
use DejwCake\SmsClient\Drivers\Clockwork;
use DejwCake\SmsClient\Drivers\Log as LogDriver;
use DejwCake\SmsClient\Drivers\Mail as MailDriver;
use DejwCake\SmsClient\Drivers\Nexmo;
use DejwCake\SmsClient\Drivers\NullDriver;
use DejwCake\SmsClient\Drivers\O2SK;
use DejwCake\SmsClient\Drivers\RequestBin;
use DejwCake\SmsClient\Drivers\TextLocal;
use DejwCake\SmsClient\Drivers\Twilio;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

/**
 * Service provider for the SMS service
 */
final class LaravelSmsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/sms.php' => config_path('sms.php'),
        ]);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->app->singleton('sms', function (Application $app) {
            $config = $app['config'];
            assert($config instanceof Config);
            $driver = $this->getDriver($config, $app['log']);

            return new Client($driver);
        });

        $this->app->bind(SmsClient::class, static fn (Application $app) => $app['sms']);
    }

    private function getDriver(Config $config, LoggerInterface $log): Driver
    {
        return match ($config->get('sms.default')) {
            'aws' => new Aws([
                'apiKey' => $config->get('sms.drivers.aws.apiKey'),
                'apiSecret' => $config->get('sms.drivers.aws.apiSecret'),
                'apiRegion' => $config->get('sms.drivers.aws.apiRegion'),
            ]),
            'clockwork' => new Clockwork(
                new GuzzleClient(),
                new GuzzleResponse(), [
                    'apiKey' => $config->get('sms.drivers.clockwork.apiKey'),
                ],
            ),
            'mail' => new MailDriver(new MailAdapter($this->app['mailer']), [
                'domain' => $config->get('sms.drivers.mail.domain'),
            ]),
            'nexmo' => new Nexmo(
                new GuzzleClient(),
                new GuzzleResponse(), [
                    'apiKey' => $config->get('sms.drivers.nexmo.apiKey'),
                    'apiSecret' => $config->get('sms.drivers.nexmo.apiSecret'),
                ],
            ),
            'null' => new NullDriver(
                new GuzzleClient(),
                new GuzzleResponse(),
            ),
            'o2sk' => new O2SK(
                new GuzzleClient(),
                [
                    'apiKey' => $config->get('sms.drivers.o2sk.apiKey'),
                ],
            ),
            'requestbin' => new RequestBin(
                new GuzzleClient(),
                new GuzzleResponse(), [
                    'path' => $config->get('sms.drivers.requestbin.path'),
                ],
            ),
            'textlocal' => new TextLocal(
                new GuzzleClient(),
                new GuzzleResponse(), [
                    'apiKey' => $config->get('sms.drivers.textlocal.apiKey'),
                ],
            ),
            'twilio' => new Twilio(
                new GuzzleClient(),
                new GuzzleResponse(), [
                    'accountId' => $config->get('sms.drivers.twilio.accountId'),
                    'apiToken' => $config->get('sms.drivers.twilio.apiToken'),
                ],
            ),
            default => new LogDriver($log),
        };
    }
}
