<?php

namespace App\Support;

use App\Models\PpdbSetting;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PpdbTempUploadManager
{
    public const SESSION_KEY = 'ppdb_temp_uploads';

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
            && Storage::disk('public')->exists($upload['path']);
    }

    public static function path(string $field): ?string
    {
        if (! self::has($field)) {
            return null;
        }

        return self::all()[$field]['path'];
    }

    /**
     * @return array<string, array{url: string, original_name: string, mime: string, is_image: bool}>
     */
    public static function forView(): array
    {
        $result = [];

        foreach (self::all() as $field => $meta) {
            if (! Storage::disk('public')->exists($meta['path'])) {
                continue;
            }

            $result[$field] = [
                'url' => Storage::disk('public')->url($meta['path']),
                'original_name' => $meta['original_name'],
                'mime' => $meta['mime'],
                'is_image' => str_starts_with($meta['mime'], 'image/'),
            ];
        }

        return $result;
    }

    public static function persistFromRequest(FormRequest $request): void
    {
        $uploads = self::all();
        $disk = Storage::disk('public');

        foreach (self::fileFieldKeys() as $field) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $file = $request->file($field);
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                continue;
            }

            if (isset($uploads[$field]['path'])) {
                $disk->delete($uploads[$field]['path']);
            }

            $uploads[$field] = [
                'path' => $file->store('ppdb/temp/'.session()->getId(), 'public'),
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

    public static function take(string $field, string $directory, string $prefix): ?string
    {
        $stored = self::path($field);
        if ($stored === null) {
            return null;
        }

        $disk = Storage::disk('public');
        $extension = pathinfo($stored, PATHINFO_EXTENSION);
        $destination = $directory.'/'.$prefix.'_'.uniqid().'.'.$extension;

        $disk->move($stored, $destination);

        $uploads = self::all();
        unset($uploads[$field]);
        session([self::SESSION_KEY => $uploads]);

        return $destination;
    }

    public static function clear(): void
    {
        $disk = Storage::disk('public');

        foreach (self::all() as $meta) {
            if (isset($meta['path'])) {
                $disk->delete($meta['path']);
            }
        }

        session()->forget(self::SESSION_KEY);
    }

    public static function disk(): Filesystem
    {
        return Storage::disk('public');
    }
}
