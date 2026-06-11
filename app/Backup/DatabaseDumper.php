<?php

namespace App\Backup;

use Exception;
use Illuminate\Support\Facades\DB;

class DatabaseDumper
{
    /**
     * Dump the database to a file. Supports SQLite and MySQL (mysqldump atau PHP native fallback).
     *
     * @throws Exception
     */
    public function dump(string $outputPath): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $this->dumpSqlite($outputPath);

            return;
        }

        if ($driver !== 'mysql') {
            throw new Exception('Driver database '.$driver.' saat ini belum didukung untuk PHP Native dumper.');
        }

        // Coba mysqldump binary (paling cepat), fallback ke PHP native
        if (! $this->tryMysqlDump($outputPath)) {
            $this->runNativePhpDump($outputPath);
        }
    }

    /**
     * Copy a SQLite database file directly.
     *
     * @throws Exception
     */
    private function dumpSqlite(string $outputPath): void
    {
        $dbPath = config('database.connections.sqlite.database');

        if (! file_exists($dbPath)) {
            throw new Exception('Database SQLite file tidak ditemukan.');
        }

        copy($dbPath, $outputPath);
    }

    /**
     * Run mysqldump menggunakan MYSQL_PWD environment variable untuk mencegah:
     *   1. Password terekspos di command line (process list / ps aux)
     *   2. Command injection via shell metacharacter di password
     *
     * Catatan: proc_open() dengan array argv hanya berfungsi di Linux/macOS.
     * Di Windows, proc_open() memerlukan string command karena CreateProcess tidak
     * mendukung argv array secara langsung — fallback ke PHP native dump digunakan.
     */
    private function tryMysqlDump(string $outputPath): bool
    {
        if (! function_exists('proc_open')) {
            return false;
        }

        // Windows tidak mendukung proc_open() dengan argv array
        // Langsung gunakan PHP native dump agar tidak error
        if (PHP_OS_FAMILY === 'Windows') {
            return false;
        }

        $host = config('database.connections.mysql.host', '127.0.0.1');
        $port = (string) config('database.connections.mysql.port', '3306');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password', '');

        // Validasi nama database — hanya boleh alfanumeric, underscore, dash
        if (! preg_match('/^[a-zA-Z0-9_\-]+$/', $database)) {
            return false;
        }

        // Pastikan mysqldump tersedia di PATH
        $which = shell_exec('which mysqldump 2>/dev/null');
        if (empty(trim((string) $which))) {
            return false;
        }

        // Gunakan argv array (bukan string) — tidak ada shell expansion sama sekali
        $cmd = [
            'mysqldump',
            '-h', $host,
            '--port='.$port,
            '-u', $username,
            $database,
        ];

        $errFile = $outputPath.'.err';

        $descriptors = [
            0 => ['pipe', 'r'],                    // stdin
            1 => ['file', $outputPath, 'w'],       // stdout → file dump
            2 => ['file', $errFile, 'w'],           // stderr → error file
        ];

        // Suntikkan password via environment variable MYSQL_PWD
        // Ini mencegah password muncul di process list
        $env = null;
        if (! empty($password)) {
            $env = array_merge($_ENV ?? [], ['MYSQL_PWD' => $password]);
        }

        $process = proc_open($cmd, $descriptors, $pipes, null, $env);

        if (! is_resource($process)) {
            if (file_exists($errFile)) {
                @unlink($errFile);
            }

            return false;
        }

        // Tutup stdin
        if (isset($pipes[0])) {
            fclose($pipes[0]);
        }

        $returnCode = proc_close($process);

        if (file_exists($errFile)) {
            @unlink($errFile);
        }

        return $returnCode === 0 && file_exists($outputPath) && filesize($outputPath) > 0;
    }

    /**
     * Pure PHP MySQL dump — menulis schema dan data via chunked selects.
     *
     * @throws Exception
     */
    private function runNativePhpDump(string $outputPath): void
    {
        $handle = fopen($outputPath, 'w');

        if (! $handle) {
            throw new Exception('Gagal membuka file temporer untuk dumping database.');
        }

        fwrite($handle, "-- MAM Limpung PHP Native Database Dump\n");
        fwrite($handle, '-- Dibuat pada: '.date('Y-m-d H:i:s')."\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
        fwrite($handle, "SET NAMES utf8mb4;\n\n");

        $tablesQuery = DB::select('SHOW TABLES');
        $dbNameKey = 'Tables_in_'.config('database.connections.mysql.database');

        foreach ($tablesQuery as $tableObj) {
            $rawTableName = $tableObj->$dbNameKey ?? current((array) $tableObj);

            // Sanitasi nama tabel — hanya izinkan karakter aman
            // Mencegah SQL injection jika ada nama tabel yang tidak terduga
            $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $rawTableName);

            if (empty($tableName)) {
                continue;
            }

            fwrite($handle, "-- -----------------------------------------------------\n");
            fwrite($handle, "-- Struktur untuk tabel: `{$tableName}`\n");
            fwrite($handle, "-- -----------------------------------------------------\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

            $createTableObj = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $createTableSql = $createTableObj[0]->{'Create Table'} ?? '';
            fwrite($handle, $createTableSql.";\n\n");

            fwrite($handle, "-- Dumping data untuk tabel: `{$tableName}`\n");

            // Dapatkan kolom pertama untuk stable ordering (diperlukan oleh chunk())
            $firstColumn = null;
            try {
                $columnsQuery = DB::select("SHOW COLUMNS FROM `{$tableName}`");
                $firstColumn = $columnsQuery[0]->Field ?? null;
            } catch (Exception) {
                // Abaikan jika tidak bisa dapatkan kolom
            }

            $query = DB::table($tableName);
            if ($firstColumn) {
                $query->orderBy($firstColumn);
            }

            $query->chunk(250, function ($rows) use ($handle, $tableName) {
                if ($rows->isEmpty()) {
                    return;
                }

                $fields = array_keys((array) $rows->first());
                $fieldsEscaped = array_map(fn ($f) => "`{$f}`", $fields);
                $fieldsList = implode(', ', $fieldsEscaped);

                fwrite($handle, "INSERT INTO `{$tableName}` ({$fieldsList}) VALUES \n");

                $rowLines = [];
                foreach ($rows as $row) {
                    $values = [];
                    foreach ((array) $row as $val) {
                        if ($val === null) {
                            $values[] = 'NULL';
                        } else {
                            // Escape karakter berbahaya di dalam string SQL
                            $escaped = str_replace(
                                ['\\', "'", "\n", "\r", "\x00", "\x1a"],
                                ['\\\\', "\\'", '\\n', '\\r', '\\0', '\\Z'],
                                (string) $val
                            );
                            $values[] = "'{$escaped}'";
                        }
                    }
                    $rowLines[] = '('.implode(', ', $values).')';
                }

                fwrite($handle, implode(",\n", $rowLines).";\n");
            });

            fwrite($handle, "\n\n");
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }
}
