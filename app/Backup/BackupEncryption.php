<?php

namespace App\Backup;

use Exception;

/**
 * BackupEncryption — Enkripsi/dekripsi file backup menggunakan AES-256-CBC.
 *
 * Format output kompatibel dengan OpenSSL: `Salted__` + salt (8 bytes) + ciphertext.
 *
 * PERBAIKAN (F-034 — Memory DoS):
 * Implementasi lama menggunakan file_get_contents() yang memuat seluruh file ke memory.
 * Untuk backup besar (>500MB), ini menyebabkan OOM (Out of Memory) error.
 *
 * Implementasi baru menggunakan streaming chunk-based (4MB per chunk) menggunakan
 * openssl_encrypt() dengan OPENSSL_RAW_DATA per blok, sehingga memory usage tetap rendah
 * berapapun ukuran file backup.
 *
 * Chunk size: 4MB (4 * 1024 * 1024 = 4194304 bytes) — harus kelipatan 16 (AES block size)
 */
class BackupEncryption
{
    /** Ukuran chunk saat streaming — 4MB, harus kelipatan 16 (AES-256-CBC block = 16 bytes) */
    private const CHUNK_SIZE = 4 * 1024 * 1024; // 4MB

    /**
     * Enkripsi file menggunakan AES-256-CBC + PBKDF2-SHA256 key derivation (streaming).
     * Output format: `Salted__` (8 bytes) + salt (8 bytes) + ciphertext chunks.
     *
     * @throws Exception
     */
    public function encryptFile(string $sourcePath, string $destPath, string $password): void
    {
        $sourceHandle = @fopen($sourcePath, 'rb');
        if ($sourceHandle === false) {
            throw new Exception('Gagal membuka berkas sumber untuk enkripsi: '.$sourcePath);
        }

        $destHandle = @fopen($destPath, 'wb');
        if ($destHandle === false) {
            fclose($sourceHandle);
            throw new Exception('Gagal membuka berkas tujuan untuk menulis hasil enkripsi: '.$destPath);
        }

        try {
            // Generate salt acak dan turunkan key + IV dari password menggunakan PBKDF2
            $salt = openssl_random_pseudo_bytes(8);
            $salted = hash_pbkdf2('sha256', $password, $salt, 10000, 48, true);
            $key = substr($salted, 0, 32);
            $iv = substr($salted, 32, 16);

            // Tulis header OpenSSL: "Salted__" + salt
            fwrite($destHandle, 'Salted__'.$salt);

            // Enkripsi data secara streaming chunk demi chunk
            // Menggunakan OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING untuk kontrol penuh
            $isLastChunk = false;
            $buffer = '';

            while (! feof($sourceHandle)) {
                $chunk = fread($sourceHandle, self::CHUNK_SIZE);
                $buffer .= $chunk;

                // Deteksi apakah ini chunk terakhir
                $isLastChunk = feof($sourceHandle);

                if ($isLastChunk) {
                    // Chunk terakhir — gunakan PKCS7 padding bawaan OpenSSL
                    $encrypted = openssl_encrypt($buffer, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
                    if ($encrypted === false) {
                        throw new Exception('Gagal mengenkripsi chunk terakhir: '.openssl_error_string());
                    }
                    fwrite($destHandle, $encrypted);
                    $buffer = '';
                } elseif (strlen($buffer) >= self::CHUNK_SIZE) {
                    // Chunk penuh — proses dengan zero padding (bukan PKCS7 untuk chunk non-terakhir)
                    // Ambil kelipatan 16 bytes, sisanya masuk buffer berikutnya
                    $processSize = intdiv(strlen($buffer), 16) * 16;
                    $toProcess = substr($buffer, 0, $processSize);
                    $buffer = substr($buffer, $processSize);

                    $encrypted = openssl_encrypt($toProcess, 'aes-256-cbc', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
                    if ($encrypted === false) {
                        throw new Exception('Gagal mengenkripsi chunk: '.openssl_error_string());
                    }

                    // Update IV untuk CBC chaining (ambil 16 bytes terakhir dari ciphertext)
                    $iv = substr($encrypted, -16);
                    fwrite($destHandle, $encrypted);
                }
            }
        } finally {
            fclose($sourceHandle);
            fclose($destHandle);
        }
    }

    /**
     * Dekripsi file AES-256-CBC yang sebelumnya dienkripsi oleh encryptFile() (streaming).
     *
     * @throws Exception
     */
    public function decryptFile(string $sourcePath, string $destPath, string $password): void
    {
        $sourceHandle = @fopen($sourcePath, 'rb');
        if ($sourceHandle === false) {
            throw new Exception('Gagal membuka berkas enkripsi sumber: '.$sourcePath);
        }

        $destHandle = @fopen($destPath, 'wb');
        if ($destHandle === false) {
            fclose($sourceHandle);
            throw new Exception('Gagal membuka berkas tujuan untuk menulis hasil dekripsi: '.$destPath);
        }

        try {
            // Baca dan validasi header (16 bytes: "Salted__" + 8 bytes salt)
            $header = fread($sourceHandle, 16);
            if ($header === false || strlen($header) < 16) {
                throw new Exception('Berkas backup terlalu kecil atau tidak terbaca.');
            }

            if (substr($header, 0, 8) !== 'Salted__') {
                throw new Exception('Format berkas backup tidak valid — header "Salted__" tidak ditemukan.');
            }

            $salt = substr($header, 8, 8);
            $salted = hash_pbkdf2('sha256', $password, $salt, 10000, 48, true);
            $key = substr($salted, 0, 32);
            $iv = substr($salted, 32, 16);

            // Dekripsi secara streaming
            // Untuk chunk non-terakhir: OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING
            // Untuk chunk terakhir: OPENSSL_RAW_DATA (supaya PKCS7 unpadding otomatis)
            $buffer = '';

            while (! feof($sourceHandle)) {
                $chunk = fread($sourceHandle, self::CHUNK_SIZE);
                $buffer .= $chunk;

                $isLastChunk = feof($sourceHandle);

                if ($isLastChunk) {
                    // Chunk terakhir — dekripsi dengan PKCS7 unpadding
                    $decrypted = openssl_decrypt($buffer, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
                    if ($decrypted === false) {
                        throw new Exception('Gagal mendekripsi — passphrase salah atau file rusak.');
                    }
                    fwrite($destHandle, $decrypted);
                    $buffer = '';
                } elseif (strlen($buffer) >= self::CHUNK_SIZE) {
                    $processSize = intdiv(strlen($buffer), 16) * 16;
                    $toProcess = substr($buffer, 0, $processSize);
                    $buffer = substr($buffer, $processSize);

                    $decrypted = openssl_decrypt($toProcess, 'aes-256-cbc', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
                    if ($decrypted === false) {
                        throw new Exception('Gagal mendekripsi chunk: '.openssl_error_string());
                    }

                    // Update IV untuk CBC chaining (ambil 16 bytes terakhir dari ciphertext yang sudah diproses)
                    $iv = substr($toProcess, -16);
                    fwrite($destHandle, $decrypted);
                }
            }
        } finally {
            fclose($sourceHandle);
            fclose($destHandle);
        }
    }
}
