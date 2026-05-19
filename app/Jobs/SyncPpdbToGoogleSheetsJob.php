<?php

namespace App\Jobs;

use App\Models\PpdbSiswa;
use App\Services\GoogleSheetsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncPpdbToGoogleSheetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah maksimal percobaan ulang jika terjadi kegagalan (misalnya limit API Google terlampaui).
     */
    public int $tries = 3;

    /**
     * Waktu tunda sebelum mencoba kembali jika gagal (dalam detik).
     */
    public int $backoff = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(protected PpdbSiswa $siswa) {}

    /**
     * Execute the job.
     */
    public function handle(GoogleSheetsService $sheetsService): void
    {
        $success = $sheetsService->appendStudent($this->siswa);

        if (! $success) {
            throw new \Exception("Gagal menambahkan calon siswa ID {$this->siswa->id} ke Google Sheets.");
        }
    }

    /**
     * Tangani kegagalan permanen setelah melampaui batas percobaan.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("Pekerjaan SyncPpdbToGoogleSheetsJob gagal permanen untuk siswa ID {$this->siswa->id}: ".$exception->getMessage());
    }
}
