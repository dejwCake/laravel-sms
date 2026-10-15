# AGENTS.md — dejwcake/laravel-sms

Laravel integration for `dejwcake/sms-client`: a service provider that builds the configured SMS
driver and binds the client. Composer `dejwcake/laravel-sms`, namespace `DejwCake\LaravelSms`
(fork of `matthewbdaly/laravel-sms`, old namespace dropped). README.md shows consumer usage.

## Layout

- `LaravelSmsProvider` — publishes `config/sms.php`, registers the `sms` singleton
  (`DejwCake\SmsClient\Client`) and binds `DejwCake\SmsClient\Contracts\Client` to it.
  `getDriver()` maps `sms.default` to a driver with a `match`; unknown values fall back to `Log`.
- `config/sms.php` — `default` (`SMS_DRIVER`) and per-driver keys read from env.
- `Mailers/MailAdapter` — `Contracts\Mailer` on top of Laravel's mailer, used by the `mail` driver.
- `Facades/Sms` — consumer-facing facade over `sms`, auto-registered via `extra.laravel.aliases`.
  It is public API: keep it, but never use it inside the package.
- Drivers themselves live in sms-client; adding one means adding it there first, then a `match`
  arm here plus its config block.

## Commands

Everything runs in Docker from the package root — never against a host PHP. The full,
copy-pasteable list (composer, every QA tool and
the "whole PHP suite" one-liner) is in **README.md → "How to develop this project"**.
The ones you need most:

```shell
docker compose run --rm test composer update
docker compose run --rm test ./vendor/bin/phpunit
docker compose run --rm php-qa phpcs -s --colors --extensions=php
docker compose run --rm php-qa phpcbf -s --colors --extensions=php       # auto-fix style
docker compose run --rm php-qa phpstan analyse --configuration=phpstan.neon
docker compose run --rm php-qa phpmd analyze
docker compose run --rm php-qa phpcs --standard=.phpcs.compatibility.xml --cache=.phpcs.cache
docker compose run --rm php-qa composer normalize
```

A change is done when phpcs, phpstan, phpmd and the test suite are green. CI runs the tests on
every supported PHP version (8.4, 8.5).

## Code conventions

- PHP `^8.4` — code must run on 8.4, so nothing 8.5-only. Laravel 12 (Testbench 10). Every file
  starts with `declare(strict_types=1);`.
- **No Facades** in package code — inject contracts through the constructor or resolve them from
  `$this->app` in the provider.
- **No helpers**, with these exceptions: `trans()` / `__()` are allowed everywhere; `app()` only
  where DI is genuinely hard to provide. (`env()` belongs in `config/` only.)
- Constructor property promotion. `final` classes and `readonly` wherever possible — prefer a
  `final readonly class`, otherwise readonly properties. A readonly property is public rather than
  hidden behind a getter.
- Always import with `use`; never inline `\Fully\Qualified\Names`.
- Alias the colliding `Repository` contracts:
  `use Illuminate\Contracts\Config\Repository as Config;`,
  `use Illuminate\Contracts\Cache\Repository as Cache;`.
- Name a property after its type, not `$service`.
- Build strings with `sprintf()` — no `"{$var}"` interpolation and no `.` concatenation.
- Mark overrides with `#[Override]` — **except** a method that overrides a *trait* method:
  PHP 8.5.3 segfaults on that.
- Before adding a native type to an overriding property/parameter, check the parent. If the parent
  is untyped (Testbench's `getPackageProviders($app)`, …) the child must stay untyped too.
- Fix new phpstan/phpmd findings in code. Baselines are for accepted, existing debt only — inspect
  the baseline diff before committing it.

## Testing conventions

- PHPUnit 12 + Orchestra Testbench 10. Tests extend `tests/TestCase.php`, which registers the
  provider and the `Sms` alias. Test namespaces mirror `src/`.
- `tests/Functional/ServiceProviderTest.php` covers each driver: set `sms.default` and its config,
  resolve `sms`, assert the driver name. A new driver gets a test there.
- Assertions are static: `self::assert*()` for new code (the existing provider test still uses
  `$this->assert*()`). Resolve services with `$this->app->make()`, never `app()`.
- Don't write tests that only re-check what sms-client already tests (driver behaviour).

## Versioning

The package is on **3.x** and requires `dejwcake/sms-client` `^3.0`: work happens on `v3`, and
`main` is fast-forwarded to it once CI is green. `main` is protected — required checks on the
exact commit, no direct pushes. Config keys, the `sms` binding and the facade are public API —
changing them is a major version.
