<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $teacherFields = ['no_telepon', 'email', 'alamat'];

    private array $studentFields = [
        'no_telepon', 'email', 'alamat',
        'nama_ayah', 'nama_ibu', 'pekerjaan_ayah', 'pekerjaan_ibu',
        'alamat_orang_tua', 'no_telepon_orang_tua',
    ];

    public function up(): void
    {
        $this->encryptTable('teachers', $this->teacherFields);
        $this->encryptTable('students', $this->studentFields);
    }

    public function down(): void
    {
        $this->decryptTable('teachers', $this->teacherFields);
        $this->decryptTable('students', $this->studentFields);
    }

    private function encryptTable(string $table, array $fields): void
    {
        DB::table($table)->orderBy('id')->chunk(100, function ($rows) use ($table, $fields) {
            foreach ($rows as $row) {
                $update = [];
                foreach ($fields as $col) {
                    if ($row->{$col} === null) {
                        continue;
                    }
                    if (! $this->isEncrypted($row->{$col})) {
                        $update[$col] = Crypt::encryptString($row->{$col});
                    }
                }
                if ($update !== []) {
                    DB::table($table)->where('id', $row->id)->update($update);
                }
            }
        });
    }

    private function decryptTable(string $table, array $fields): void
    {
        DB::table($table)->orderBy('id')->chunk(100, function ($rows) use ($table, $fields) {
            foreach ($rows as $row) {
                $update = [];
                foreach ($fields as $col) {
                    if ($row->{$col} === null) {
                        continue;
                    }
                    if ($this->isEncrypted($row->{$col})) {
                        $update[$col] = Crypt::decryptString($row->{$col});
                    }
                }
                if ($update !== []) {
                    DB::table($table)->where('id', $row->id)->update($update);
                }
            }
        });
    }

    private function isEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);

            return true;
        } catch (Exception) {
            return false;
        }
    }
};
