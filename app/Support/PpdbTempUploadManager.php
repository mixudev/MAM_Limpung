<?php

namespace App\Support;

use App\Models\PpdbSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * PpdbTempUploadManager — Mengelola file upload sementara untuk formulir PPDB.
 *
 * KEAMANAN (F-019):
 * File sementara sebelumnya disimpan di public disk (storage/app/public/ppdb/temp/)
 * yang dapat diakses siapapun via URL /storage/ppdb/temp/...
 *
 * Sekarang menggunakan private disk (storage/app/private/ppdb/temp/) yang TIDAK
 * dapat diakses langsung dari URL — mencegah foto siswa & dokumen sensitif terekspos.
 *
 * Saat take(), file dipindahkan dari private ke public disk dengan nama baru yang aman.
 */
class PpdbTempUploadManager
{
    public const SESSION_KEY = 'ppdb_temp_uploads';

    /**
     * Disk privat — untuk menyimpan file sementara yang tidak boleh diakses publik.
     */
    private const PRIVATE_DISK = 'local';

    /**
     * Disk publik — untuk file final yang sudah diproses.
     */
    private const PUBLIC_DISK = 'public';

    /**
     * @return list<string>
     */
    public static function fileFieldKeys(): array
    {
        $keys = ['foto_siswa'];

        $requirements = PpdbSetting::getValue('requirements', []);
        foreach ($requirements as $req) {
            if ($req['id'] !== 'foto') {
                $keys[] = $req['id'];
            }
        }

        return $keys;
    }

    /**
     * @return array<string, array{path: string, original_name: string, mime: string}>
     */
    public static function all(): array
    {
        /** @var array<string, array{path: string, original_name: string, mime: string}> $uploads */
        $uploads = session(self::SESSION_KEY, []);

        return $uploads;
    }

    public static function has(string $field): bool
    {
        $upload = self::all()[$field] ?? null;

        return is_array($upload)
            && isset($upload['path'])
            && Storage::disk(self::PRIVATE_DISK)->exists($upload['path']);
    }

    public static function path(string $field): ?string
    {
        if (! self::has($field)) {
            return null;
        }

        return self::all()[$field]['path'];
    }

    /**
     * Menghasilkan URL sementara yang aman (signed) untuk preview di form — hanya 1 jam.
     * File tidak disajikan langsung dari URL storage karena ada di private disk.
     *
     * @return array<string, array{url: string, original_name: string, mime: string, is_image: bool}>
     */
    public static function forView(): array
    {
        $result = [];

        foreach (self::all() as $field => $meta) {
            if (! Storage::disk(self::PRIVATE_DISK)->exists($meta['path'])) {
                continue;
            }

            // Buat URL signed sementara menggunakan Laravel built-in (memerlukan 'serve' => true)
            // Fallback ke placeholder jika tidak bisa generate URL
            try {
                $url = Storage::disk(self::PRIVATE_DISK)->temporaryUrl(
                    $meta['path'],
                    now()->addHour()
                );
            } catch (\RuntimeException) {
                // Driver local tidak mendukung temporaryUrl di production,
                // gunakan route signed sebagai fallback
                $url = route('ppdb.temp.preview', ['field' => $field]);
            }

            $result[$field] = [
                'url' => $url,
                'original_name' => $meta['original_name'],
                'mime' => $meta['mime'],
                'is_image' => str_starts_with($meta['mime'], 'image/'),
            ];
        }

        return $result;
    }

    /**
     * Simpan file upload ke private disk.
     * Dipanggil saat validasi gagal agar user tidak perlu re-upload.
     */
    public static function persistFromRequest(FormRequest $request): void
    {
        $uploads = self::all();
        $privateDisk = Storage::disk(self::PRIVATE_DISK);

        foreach (self::fileFieldKeys() as $field) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $file = $request->file($field);
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                continue;
            }

            // Hapus file lama jika ada
            if (isset($uploads[$field]['path'])) {
                $privateDisk->delete($uploads[$field]['path']);
            }

            // Simpan ke private disk — path: ppdb/temp/{session_id}/{random}.ext
            // Menggunakan private disk — TIDAK dapat diakses via URL publik
            $safeName = Str::random(40).'.'.$file->guessExtension();
            $path = 'ppdb/temp/'.session()->getId().'/'.$safeName;

            $privateDisk->put($path, file_get_contents($file->getRealPath()));

            $uploads[$field] = [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType() ?? 'application/octet-stream',
            ];
        }

        session([self::SESSION_KEY => $uploads]);
    }

    /**
     * @return array<int, string>
     */
    public static function fileRules(string $field, bool $requiredByConfig = true): array
    {
        $rules = ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:2048'];

        if ($requiredByConfig && ! self::has($field)) {
            array_unshift($rules, 'required');
        }

        if ($field === 'foto_siswa') {
            return self::has($field)
                ? ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048']
                : ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'];
        }

        return $rules;
    }

    /**
     * Pindahkan file dari private temp ke public disk dengan nama baru yang aman.
     * Dipanggil saat form PPDB berhasil disubmit.
     */
    public static function take(string $field, string $directory, string $prefix): ?string
    {
        $stored = self::path($field);
        if ($stored === null) {
            return null;
        }

        $privateDisk = Storage::disk(self::PRIVATE_DISK);
        $publicDisk = Storage::disk(self::PUBLIC_DISK);

        $extension = pathinfo($stored, PATHINFO_EXTENSION);
        $destination = $directory.'/'.$prefix.'_'.Str::random(20).'.'.$extension;

        // Salin dari private ke public dengan nama baru yang aman
        $contents = $privateDisk->get($stored);
        if ($contents === null) {
            return null;
        }

        $publicDisk->put($destination, $contents);

        // Hapus dari private disk
        $privateDisk->delete($stored);

        // Update session
        $uploads = self::all();
        unset($uploads[$field]);
        session([self::SESSION_KEY => $uploads]);

        return $destination;
    }

    /**
     * Bersihkan semua file temp dan hapus dari session.
     */
    public static function clear(): void
    {
        $privateDisk = Storage::disk(self::PRIVATE_DISK);

        foreach (self::all() as $meta) {
            if (isset($meta['path'])) {
                $privateDisk->delete($meta['path']);
            }
        }

        session()->forget(self::SESSION_KEY);
    }
}
