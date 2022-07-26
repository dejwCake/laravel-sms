<?php

declare(strict_types=1);

namespace Matthewbdaly\LaravelSMS;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Support\ServiceProvider;
use Matthewbdaly\LaravelSMS\Mailers\MailAdapter;
use Matthewbdaly\SMS\Client;
use Matthewbdaly\SMS\Contracts\Client as SmsClient;
use Matthewbdaly\SMS\Drivers\Aws;
use Matthewbdaly\SMS\Drivers\Clockwork;
use Matthewbdaly\SMS\Drivers\Log as LogDriver;
use Matthewbdaly\SMS\Drivers\Mail as MailDriver;
use Matthewbdaly\SMS\Drivers\Nexmo;
use Matthewbdaly\SMS\Drivers\NullDriver;
use Matthewbdaly\SMS\Drivers\O2SK;
use Matthewbdaly\SMS\Drivers\RequestBin;
use Matthewbdaly\SMS\Drivers\TextLocal;
use Matthewbdaly\SMS\Drivers\Twilio;

/**
 * Service provider for the SMS service
 */
final class LaravelSMSProvider extends ServiceProvider
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
        $this->app->singleton('sms', static function ($app) {
            $config = $app['config'];
            $driver = match ($config['sms.default']) {
                'aws' => new Aws([
                    'apiKey' => $config['sms.drivers.aws.apiKey'],
                    'apiSecret' => $config['sms.drivers.aws.apiSecret'],
                    'apiRegion' => $config['sms.drivers.aws.apiRegion'],
                ]),
                'clockwork' => new Clockwork(
                    new GuzzleClient(),
                    new GuzzleResponse(),
                    [
                        'apiKey' => $config['sms.drivers.clockwork.apiKey'],
                    ],
                ),
                'mail' => new MailDriver(new MailAdapter(), [
                    'domain' => $config['sms.drivers.mail.domain'],
                ]),
                'nexmo' => new Nexmo(
                    new GuzzleClient(),
                    new GuzzleResponse(),
                    [
                        'apiKey' => $config['sms.drivers.nexmo.apiKey'],
                        'apiSecret' => $config['sms.drivers.nexmo.apiSecret'],
                    ],
                ),
                'null' => new NullDriver(
                    new GuzzleClient(),
                    new GuzzleResponse(),
                ),
                'o2sk' => new O2SK(
                    new GuzzleClient(),
                    [
                        'apiKey' => $config['sms.drivers.o2sk.apiKey'],
                    ],
                ),
                'requestbin' => new RequestBin(
                    new GuzzleClient(),
                    new GuzzleResponse(),
                    [
                        'path' => $config['sms.drivers.requestbin.path'],
                    ],
                ),
                'textlocal' => new TextLocal(
                    new GuzzleClient(),
                    new GuzzleResponse(),
                    [
                        'apiKey' => $config['sms.drivers.textlocal.apiKey'],
                    ],
                ),
                'twilio' => new Twilio(
                    new GuzzleClient(),
                    new GuzzleResponse(),
                    [
                        'accountId' => $config['sms.drivers.twilio.accountId'],
                        'apiToken' => $config['sms.drivers.twilio.apiToken'],
                    ],
                ),
                default => new LogDriver(
                    $app['log'],
                ),
            };

            return new Client($driver);
        });

        $this->app->bind(SmsClient::class, static fn ($app) => $app['sms']);
    }
}
