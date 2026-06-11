<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class BaseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Retrieve SMTP config from Laravel's standard mail config (driven by .env).
     *
     * @return array<string, mixed>
     */
    public static function getSmtpConfig(): array
    {
        return [
            'host' => config('mail.mailers.smtp.host', '127.0.0.1'),
            'port' => (int) config('mail.mailers.smtp.port', 587),
            'username' => config('mail.mailers.smtp.username'),
            'password' => config('mail.mailers.smtp.password'),
            'encryption' => config('mail.mailers.smtp.scheme', 'tls'),
            'from_address' => config('mail.from.address', ''),
            'from_name' => config('mail.from.name', config('app.name')),
        ];
    }

    /**
     * Check whether SMTP has been configured via .env.
     */
    public static function isConfigured(): bool
    {
        return ! empty(config('mail.mailers.smtp.host'))
            && config('mail.mailers.smtp.host') !== '127.0.0.1'
            && ! empty(config('mail.mailers.smtp.username'));
    }

    /**
     * Build a dynamic mailer transport config array for Mail::build()->send().
     *
     * @return array<string, mixed>
     */
    public static function buildTransportConfig(): array
    {
        $cfg = self::getSmtpConfig();

        return [
            'transport' => 'smtp',
            'host' => $cfg['host'],
            'port' => $cfg['port'],
            'username' => $cfg['username'],
            'password' => $cfg['password'],
            'encryption' => $cfg['encryption'],
            'timeout' => 10,
        ];
    }
}
