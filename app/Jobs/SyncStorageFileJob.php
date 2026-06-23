<?php

namespace App\Jobs;

use App\Services\StorageSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncStorageFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        protected string $relativePath,
    ) {}

    public function handle(StorageSyncService $syncService): void
    {
        $syncService->syncFile($this->relativePath);
    }

    public function failed(Throwable $exception): void
    {
        Log::error("SyncStorageFileJob: Gagal permanen sync file {$this->relativePath}: {$exception->getMessage()}");
    }
}
