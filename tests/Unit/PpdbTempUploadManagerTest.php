<?php

use App\Support\PpdbTempUploadManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Storage::fake('public');
    session()->flush();
});

test('temp upload manager stores and retrieves file metadata in session', function () {
    $file = UploadedFile::fake()->create('foto.jpg', 100, 'image/jpeg');

    $path = $file->store('ppdb/temp/test', 'public');

    session([
        PpdbTempUploadManager::SESSION_KEY => [
            'foto_siswa' => [
                'path' => $path,
                'original_name' => 'foto.jpg',
                'mime' => 'image/jpeg',
            ],
        ],
    ]);

    expect(PpdbTempUploadManager::has('foto_siswa'))->toBeTrue()
        ->and(PpdbTempUploadManager::path('foto_siswa'))->toBe($path)
        ->and(PpdbTempUploadManager::forView()['foto_siswa']['is_image'])->toBeTrue();
});

test('file rules require upload only when no temp file exists', function () {
    expect(PpdbTempUploadManager::fileRules('foto_siswa'))->toContain('required');

    session([
        PpdbTempUploadManager::SESSION_KEY => [
            'foto_siswa' => [
                'path' => UploadedFile::fake()->create('f.jpg', 100, 'image/jpeg')->store('ppdb/temp/x', 'public'),
                'original_name' => 'f.jpg',
                'mime' => 'image/jpeg',
            ],
        ],
    ]);

    expect(PpdbTempUploadManager::fileRules('foto_siswa'))->not->toContain('required')
        ->and(PpdbTempUploadManager::fileRules('foto_siswa'))->toContain('nullable');
});

test('take moves temp file to destination and removes from session', function () {
    $file = UploadedFile::fake()->create('doc.jpg', 100, 'image/jpeg');
    $path = $file->store('ppdb/temp/test', 'public');

    session([
        PpdbTempUploadManager::SESSION_KEY => [
            'kk' => [
                'path' => $path,
                'original_name' => 'doc.jpg',
                'mime' => 'image/jpeg',
            ],
        ],
    ]);

    $final = PpdbTempUploadManager::take('kk', 'ppdb/requirements', 'req_kk');

    expect($final)->not->toBeNull()
        ->and(Storage::disk('public')->exists($final))->toBeTrue()
        ->and(PpdbTempUploadManager::has('kk'))->toBeFalse();
});
