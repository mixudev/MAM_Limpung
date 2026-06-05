<?php

namespace App\Mail;

use App\Models\SecuritySetting;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

abstract class BaseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Retrieve decrypted SMTP config from SecuritySetting.
     *
     * @return array<string, mixed>
     */
    public static function getSmtpConfig(): array
    {
        $credentials = SecuritySetting::getValue('smtp_credentials', []);
        $defaults = [
            'host' => config('mail.mailers.smtp.host', 'smtp.gmail.com'),
            'port' => config('mail.mailers.smtp.port', 587),
            'username' => config('mail.from.address', ''),
            'password' => '',
            'encryption' => config('mail.mailers.smtp.encryption', 'tls'),
            'from_address' => config('mail.from.address', ''),
            'from_name' => config('mail.from.name', config('app.name')),
        ];

        if (empty($credentials)) {
            return $defaults;
        }

        $decrypted = $credentials;

        // Decrypt password if stored encrypted
        if (! empty($credentials['password_encrypted'])) {
            try {
                $decrypted['password'] = Crypt::decryptString($credentials['password_encrypted']);
            } catch (Exception $e) {
                Log::warning('BaseMail: Gagal mendekripsi password SMTP: '.$e->getMessage());
                $decrypted['password'] = '';
            }
        }

        return array_merge($defaults, $decrypted);
    }

    /**
     * Check whether SMTP has been configured by admin.
     */
    public static function isConfigured(): bool
    {
        $credentials = SecuritySetting::getValue('smtp_credentials', []);

        return ! empty($credentials['host']) && ! empty($credentials['username']);
    }

    /**
     * Build a dynamic mailer transport config array for Mail::mailer()->send().
     *
     * @return array<string, mixed>
     */
    public static function buildTransportConfig(): array
    {
        $cfg = self::getSmtpConfig();

        return [
            'transport' => 'smtp',
            'host' => $cfg['host'],
            'port' => (int) $cfg['port'],
            'username' => $cfg['username'],
            'password' => $cfg['password'],
            'encryption' => $cfg['encryption'],
            'timeout' => 3, // Dioptimalkan menjadi 3 detik
        ];
    }
}
