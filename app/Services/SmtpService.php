<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use App\Mail\BaseMail;
use App\Mail\System\TestConnectionMail;
use Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SmtpService
{
    /**
     * Send a Mailable using the admin-configured SMTP credentials.
     * Falls back to default Laravel mailer if SMTP is not configured.
     *
     * Usage:
     *   app(SmtpService::class)->send(new PpdbRegistrationMail($siswa), $siswa->email);
     *
     * @throws Exception
     */
    public function send(Mailable $mailable, string $toEmail, ?string $toName = null): void
    {
        // Jika driver queue bukan sync dan bukan TestConnectionMail, dispatch ke queue agar respons instan
        if (config('queue.default') !== 'sync' && ! $mailable instanceof TestConnectionMail) {
            SendEmailJob::dispatch($mailable, $toEmail, $toName);

            return;
        }

        $this->sendNow($mailable, $toEmail, $toName);
    }

    /**
     * Mengirimkan email secara sinkron (langsung).
     *
     * @throws Exception
     */
    public function sendNow(Mailable $mailable, string $toEmail, ?string $toName = null): void
    {
        if (! BaseMail::isConfigured()) {
            throw new Exception('Konfigurasi SMTP belum diatur. Silakan isi MAIL_HOST, MAIL_USERNAME, dan MAIL_PASSWORD di file .env server.');
        }

        $cfg = BaseMail::getSmtpConfig();

        $mailer = Mail::build([
            'transport' => 'smtp',
            'host' => $cfg['host'],
            'port' => (int) $cfg['port'],
            'username' => $cfg['username'],
            'password' => $cfg['password'],
            'encryption' => $cfg['encryption'],
            'timeout' => 3, // Dioptimalkan menjadi 3 detik agar tidak memblokir proses terlalu lama jika gagal
        ]);

        $mailable->from($cfg['from_address'], $cfg['from_name']);

        if ($toName) {
            $mailer->to($toEmail, $toName)->send($mailable);
        } else {
            $mailer->to($toEmail)->send($mailable);
        }

        Log::info('SmtpService: Email ['.get_class($mailable).'] berhasil dikirim ke '.$toEmail);
    }

    /**
     * Send a test connection email to verify SMTP credentials.
     *
     * @throws Exception
     */
    public function testConnection(string $toEmail): void
    {
        $testMail = new TestConnectionMail;
        $this->send($testMail, $toEmail);
    }

    /**
     * Silently send — logs the error but does NOT throw.
     * Use this for non-critical notifications (e.g. observers, jobs).
     */
    public function sendQuiet(Mailable $mailable, string $toEmail, ?string $toName = null): bool
    {
        try {
            $this->send($mailable, $toEmail, $toName);

            return true;
        } catch (Exception $e) {
            Log::error('SmtpService: Gagal mengirim email ['.get_class($mailable).'] ke '.$toEmail.': '.$e->getMessage());

            return false;
        }
    }
}
