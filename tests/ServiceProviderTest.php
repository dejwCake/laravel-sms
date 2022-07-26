<?php

declare(strict_types=1);

namespace Tests;

use Matthewbdaly\LaravelSMS\Facades\Sms;
use Matthewbdaly\SMS\Client;
use Matthewbdaly\SMS\Contracts\Client as ClientContract;
use Mockery;

final class ServiceProviderTest extends TestCase
{
    public function testAwsSnsDriverSetup(): void
    {
        $this->app['config']->set('sms.default', 'aws');
        $this->app['config']->set('sms.drivers.aws.apiKey', 'MY_AWS_SNS_API_KEY');
        $this->app['config']->set('sms.drivers.aws.apiSecret', 'MY_AWS_SNS_API_SECRET');
        $this->app['config']->set('sms.drivers.aws.apiRegion', 'eu-west-2');
        $client = $this->app->make('sms');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('Aws', $client->getDriver());
    }

    public function testClockworkDriverSetup(): void
    {
        $this->app['config']->set('sms.default', 'clockwork');
        $this->app['config']->set('sms.drivers.clockwork.apiKey', 'MY_CLOCKWORK_API_KEY');
        $client = $this->app->make('sms');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('Clockwork', $client->getDriver());
    }

    public function testLogDriverSetup(): void
    {
        $this->app['config']->set('sms.default', 'log');
        $client = $this->app->make('sms');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('Log', $client->getDriver());
    }

    public function testMailDriverSetup(): void
    {
        $this->app['config']->set('sms.default', 'mail');
        $this->app['config']->set('sms.drivers.mail.domain', 'my.sms-gateway.com');
        $client = $this->app->make('sms');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('Mail', $client->getDriver());
    }

    public function testNexmoDriverSetup(): void
    {
        $this->app['config']->set('sms.default', 'nexmo');
        $this->app['config']->set('sms.drivers.nexmo.apiKey', 'MY_NEXMO_API_KEY');
        $this->app['config']->set('sms.drivers.nexmo.apiSecret', 'MY_NEXMO_API_SECRET');
        $client = $this->app->make('sms');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('Nexmo', $client->getDriver());
    }

    public function testNullDriverSetup(): void
    {
        $this->app['config']->set('sms.default', 'null');
        $client = $this->app->make('sms');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('Null', $client->getDriver());
    }

    public function testO2SKDriverSetup(): void
    {
        $this->app['config']->set('sms.default', 'o2sk');
        $this->app['config']->set('sms.drivers.o2sk.apiKey', 'O2_SK_API_KEY');
        $client = $this->app->make('sms');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('O2SK', $client->getDriver());
    }

    public function testRequestBinDriverSetup(): void
    {
        $this->app['config']->set('sms.default', 'requestbin');
        $this->app['config']->set('sms.drivers.requestbin.path', 'REQUESTBIN_PATH');
        $client = $this->app->make('sms');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('RequestBin', $client->getDriver());
    }

    public function testTextLocalDriverSetup(): void
    {
        $this->app['config']->set('sms.default', 'textlocal');
        $this->app['config']->set('sms.drivers.textlocal.apiKey', 'MY_TEXTLOCAL_API_KEY');
        $client = $this->app->make('sms');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('TextLocal', $client->getDriver());
    }

    public function testTwilioDriverSetup(): void
    {
        $this->app['config']->set('sms.default', 'twilio');
        $this->app['config']->set('sms.drivers.twilio.accountId', 'MY_TWILIO_ACCOUNT_ID');
        $this->app['config']->set('sms.drivers.twilio.apiToken', 'MY_TWILIO_API_TOKEN');
        $client = $this->app->make('sms');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('Twilio', $client->getDriver());
    }

    public function testDefaultDriverSetup(): void
    {
        $client = $this->app->make('sms');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('Log', $client->getDriver());
    }

    public function testFacade(): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $mock = Mockery::mock(ClientContract::class);
        $mock->shouldReceive('send')
            ->with($msg)
            ->once()
            ->andReturn(true);
        $this->app->instance('sms', $mock);
        Sms::send($msg);
    }

    public function testInject(): void
    {
        $client = $this->app->make(ClientContract::class);
        $this->assertInstanceOf(ClientContract::class, $client);
        $this->assertInstanceOf(Client::class, $client);
    }
}
