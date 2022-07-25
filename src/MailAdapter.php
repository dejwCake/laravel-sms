<?php

declare(strict_types=1);

namespace Matthewbdaly\LaravelSMS;

use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Matthewbdaly\SMS\Contracts\Mailer;

/**
 * Wrapper for the Laravel Mail interface to use it to send emails to the SMS gateway
 */
final class MailAdapter implements Mailer
{
    /**
     * Send email
     *
     * @param string $recipient The recipent's email.
     * @param string $message   The message.
     */
    public function send(string $recipient, string $message): bool
    {
        $mailer = app()->make(MailerContract::class);

        return $mailer->to($recipient)->raw($message);
    }
}
