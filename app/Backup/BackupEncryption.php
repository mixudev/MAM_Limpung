<?php

namespace App\Backup;

use Exception;

class BackupEncryption
{
    /**
     * Encrypt a file using AES-256-CBC with PBKDF2-SHA256 key derivation.
     * Output format is OpenSSL-compatible: `Salted__` + salt (8 bytes) + ciphertext.
     *
     * @throws Exception
     */
    public function encryptFile(string $sourcePath, string $destPath, string $password): void
    {
        $salt = openssl_random_pseudo_bytes(8);
        $salted = hash_pbkdf2('sha256', $password, $salt, 10000, 48, true);
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);

        $data = file_get_contents($sourcePath);

        if ($data === false) {
            throw new Exception('Gagal membaca berkas sumber raw zip.');
        }

        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new Exception('Gagal mengenkripsi berkas menggunakan AES-256 OpenSSL.');
        }

        // OpenSSL magic header: Salted__ (8 bytes) + salt (8 bytes) + ciphertext
        $output = 'Salted__'.$salt.$encrypted;

        if (file_put_contents($destPath, $output) === false) {
            throw new Exception('Gagal menulis berkas backup terenkripsi ke penyimpanan.');
        }
    }

    /**
     * Decrypt an AES-256-CBC file previously encrypted by `encryptFile()`.
     *
     * @throws Exception
     */
    public function decryptFile(string $sourcePath, string $destPath, string $password): void
    {
        $data = file_get_contents($sourcePath);

        if ($data === false) {
            throw new Exception('Gagal membaca berkas enkripsi sumber.');
        }

        if (substr($data, 0, 8) !== 'Salted__') {
            throw new Exception('Format berkas backup tidak valid atau tidak terenkripsi menggunakan format standard OpenSSL.');
        }

        $salt = substr($data, 8, 8);
        $encrypted = substr($data, 16);

        $salted = hash_pbkdf2('sha256', $password, $salt, 10000, 48, true);
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);

        $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            throw new Exception('Sandi (Passphrase) dekripsi salah atau berkas backup terenkripsi rusak.');
        }

        if (file_put_contents($destPath, $decrypted) === false) {
            throw new Exception('Gagal menulis berkas raw zip hasil dekripsi.');
        }
    }
}
