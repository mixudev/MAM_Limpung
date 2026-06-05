<?php

namespace App\Jobs;

use App\Services\SmtpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah maksimal percobaan ulang jika terjadi kegagalan.
     */
    public int $tries = 3;

    /**
     * Waktu tunda sebelum mencoba kembali jika gagal (dalam detik).
     */
    public int $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Mailable $mailable,
        protected string $toEmail,
        protected ?string $toName = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(SmtpService $smtpService): void
    {
        $smtpService->sendNow($this->mailable, $this->toEmail, $this->toName);
    }

    /**
     * Tangani kegagalan permanen setelah melampaui batas percobaan.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("SendEmailJob: Gagal mengirim email ke {$this->toEmail} secara permanen: ".$exception->getMessage());
    }
}
