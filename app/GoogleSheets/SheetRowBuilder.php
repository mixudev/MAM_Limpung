<?php

namespace App\GoogleSheets;

use App\Models\PpdbSiswa;

class SheetRowBuilder
{
    /**
     * Build a row data array for a single student based on selected fields.
     *
     * @param  array<string>  $syncFields
     * @param  array<int, array<string, mixed>>  $customFields
     * @return array<int, string>
     */
    public function buildRowData(PpdbSiswa $siswa, array $syncFields, array $customFields): array
    {
        $rowData = [];

        if (in_array('no_registrasi', $syncFields)) {
            $rowData[] = $siswa->nomor_registrasi;
        }
        if (in_array('nama_lengkap', $syncFields)) {
            $rowData[] = $siswa->nama_lengkap;
        }
        if (in_array('nisn', $syncFields)) {
            $rowData[] = $siswa->nisn;
        }
        if (in_array('jenis_kelamin', $syncFields)) {
            $rowData[] = $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
        }
        if (in_array('sekolah_asal', $syncFields)) {
            $rowData[] = $siswa->sekolah_asal;
        }
        if (in_array('no_hp', $syncFields)) {
            $rowData[] = $siswa->nomor_hp;
        }
        if (in_array('email', $syncFields)) {
            $rowData[] = $siswa->email ?? '-';
        }
        if (in_array('status', $syncFields)) {
            $rowData[] = strtoupper($siswa->status ?? 'PENDING');
        }
        if (in_array('tanggal_daftar', $syncFields)) {
            $rowData[] = $siswa->submitted_at?->format('d-m-Y H:i') ?? '-';
        }

        // Add custom fields
        if (in_array('custom_fields', $syncFields) && ! empty($customFields)) {
            foreach ($customFields as $field) {
                $val = $siswa->additional_fields[$field['id']] ?? '';
                if (is_array($val)) {
                    $val = implode(', ', $val);
                }
                $rowData[] = $val;
            }
        }

        return $rowData;
    }

    /**
     * Build the headers array based on selected fields.
     *
     * @param  array<string>  $syncFields
     * @param  array<int, array<string, mixed>>  $customFields
     * @return array<int, string>
     */
    public function buildHeaders(array $syncFields, array $customFields): array
    {
        $headers = [];

        if (in_array('no_registrasi', $syncFields)) {
            $headers[] = 'No. Registrasi';
        }
        if (in_array('nama_lengkap', $syncFields)) {
            $headers[] = 'Nama Lengkap';
        }
        if (in_array('nisn', $syncFields)) {
            $headers[] = 'NISN';
        }
        if (in_array('jenis_kelamin', $syncFields)) {
            $headers[] = 'Jenis Kelamin';
        }
        if (in_array('sekolah_asal', $syncFields)) {
            $headers[] = 'Sekolah Asal';
        }
        if (in_array('no_hp', $syncFields)) {
            $headers[] = 'No. HP / WA';
        }
        if (in_array('email', $syncFields)) {
            $headers[] = 'Email';
        }
        if (in_array('status', $syncFields)) {
            $headers[] = 'Status Seleksi';
        }
        if (in_array('tanggal_daftar', $syncFields)) {
            $headers[] = 'Tanggal Daftar';
        }

        // Add custom field labels
        if (in_array('custom_fields', $syncFields) && ! empty($customFields)) {
            foreach ($customFields as $field) {
                $headers[] = $field['id'] === 'nama_wali' ? 'Nama Wali' : $field['label'];
            }
        }

        return $headers;
    }
}
