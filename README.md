# laravel-sms

SMS service provider for Laravel and Lumen. Uses [SMS Client](https://github.com/dejwCake/sms-client) to enable sending SMS messages using the following drivers:

* `nexmo`
* `clockwork`
* `textlocal`
* `twilio`
* `O2SK`
* `aws` (requires installation of `aws/aws-sdk-php`)
* `mail` (somewhat untested and may be too generic to be much use)

Also has the following drivers for testing purposes:

* `log`
* `null`
* `requestbin`

Fork note
---------

This project is a fork of `Matthewbdaly\LaravelSMS`. It is maintained and improved by David Běhal (`DejwCake`).

Namespace change
----------------

The library namespace has been updated from `Matthewbdaly\LaravelSMS` to `DejwCake\LaravelSms`. Backward compatibility with the old namespace has been removed; please update your imports accordingly.


Installation for Laravel
------------------------

This package is only intended for Laravel 5.5 and up. Install it with the following command:

```bash
$ composer require dejwcake/laravel-sms
```

Then publish the config file:

```bash
$ php artisan vendor:publish
```

You will need to select the service provider `Dejwcake\LaravelSms\LaravelSmsProvider`. Then set your driver and any settings required in the `.env` file for your project:

```
SMS_DRIVER=nexmo
NEXMO_API_KEY=foo
NEXMO_API_SECRET=bar
CLOCKWORK_API_KEY=baz
TEXTLOCAL_API_KEY=baz
REQUESTBIN_PATH=foo
O2_SK_API_KEY=foo
AWS_SNS_API_KEY=foo
AWS_SNS_API_SECRET=bar
AWS_SNS_API_REGION=baz
MAIL_SMS_DOMAIN=my.sms-gateway.com
TWILIO_ACCOUNT_ID=foo
TWILIO_API_TOKEN=bar
```

Installation for Lumen
----------------------

The installation process with Lumen is identical to that for Laravel, although if you wish to use the facade you will need to uncomment the appropriate section of `bootstrap/app.php` as usual.

Usage
-----

Once the package is installed and configured, you can use the facade to send SMS messages:

```php
use DejwCake\SmsClient\Facades\Sms;

$msg = [
    'to'      => '+44 01234 567890',
    'content' => 'Just testing',
];
Sms::send($msg);
```

Or fetch it from the app:

```php
$msg = [
    'to'      => '+44 01234 567890',
    'content' => 'Just testing',
];
$sms = app()['sms'];
$sms->send($msg);
```

Or resolve the interface `DejwCake\SmsClient\Contracts\Client`:

```php
$msg = [
    'to'      => '+44 01234 567890',
    'content' => 'Just testing',
];
$sms = app()->make('DejwCake\SmsClient\Contracts\Client');
$sms->send($msg);
```

Here we use the `app()` helper, but you'll normally want to inject it into a constructor or method of another class.

## How to develop this project

### Composer

Update dependencies:
```shell
docker compose run --rm cli composer update
```

Composer normalization:
```shell
docker compose run --rm php-qa composer normalize
```

### Run tests

Run tests with pcov:
```shell
docker compose run --rm test ./vendor/bin/phpunit -d pcov.enabled=1
```

### Run code analysis tools (php-qa)

PHP compatibility:
```shell
docker compose run --rm php-qa phpcs --standard=.phpcs.compatibility.xml --cache=.phpcs.cache
```

Code style:
```shell
docker compose run --rm php-qa phpcs -s --colors --extensions=php
```

Fix style issues:
```shell
docker compose run --rm php-qa phpcbf -s --colors --extensions=php
```

Static analysis (phpstan):
```shell
docker compose run --rm php-qa phpstan analyse --configuration=phpstan.neon
```

Mess detector (phpmd):
```shell
docker compose run --rm php-qa phpmd ./src,./tests ansi phpmd.xml --suffixes php --baseline-file phpmd.baseline.xml
```
