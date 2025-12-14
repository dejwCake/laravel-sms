<?php

declare(strict_types=1);

namespace DejwCake\LaravelSms\Mailers;

use DejwCake\SmsClient\Contracts\Mailer;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Mail\Message;
use Throwable;

/**
 * Wrapper for the Laravel Mail interface to use it to send emails to the SMS gateway
 */
final readonly class MailAdapter implements Mailer
{
    public function __construct(private MailerContract $mailer)
    {
    }

    public function send(string $recipient, string $message): bool
    {
        try {
            $this->mailer->raw($message, static function (Message $mail) use ($recipient): void {
                $mail->to($recipient);
            });

            return true;
        } catch (Throwable) {
            // Optionally log the error here
            return false;
        }
    }
}
