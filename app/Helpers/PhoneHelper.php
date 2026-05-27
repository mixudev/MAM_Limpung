<?php

if (! function_exists('normalize_phone_id')) {
    /**
     * Normalisasi nomor HP Indonesia ke format WhatsApp (62xxxxxxxxxx)
     */
    function normalize_phone_id(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        // Hapus semua karakter selain angka
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika diawali 0 → ganti 62
        if (str_starts_with($phone, '0')) {
            return '62'.substr($phone, 1);
        }

        // Jika diawali 62 → biarkan
        if (str_starts_with($phone, '62')) {
            return $phone;
        }

        // Jika diawali 8 → tambahkan 62
        if (str_starts_with($phone, '8')) {
            return '62'.$phone;
        }

        return null;
    }
}
