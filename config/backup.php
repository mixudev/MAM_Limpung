<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup Encryption Key
    |--------------------------------------------------------------------------
    |
    | Kunci AES-256 untuk mengenkripsi file backup. Harus diisi di .env server.
    | Generate dengan: php artisan tinker --execute "echo base64_encode(random_bytes(32));"
    |
    | PENTING: Simpan kunci ini di luar server (password manager / vault).
    | Kehilangan kunci = kehilangan akses ke semua file backup terenkripsi.
    |
    */

    'encryption_key' => env('BACKUP_ENCRYPTION_KEY'),

];
