<?php

namespace App\Backup;

use Exception;
use Illuminate\Support\Facades\DB;

class DatabaseDumper
{
    /**
     * Dump the database to a file. Supports SQLite and MySQL (mysqldump or PHP native fallback).
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

        // Try mysqldump binary first (fastest), fall back to native PHP
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
     * Run mysqldump command-line as a high-performance primary dumper.
     */
    private function tryMysqlDump(string $outputPath): bool
    {
        if (! function_exists('exec')) {
            return false;
        }

        $host = config('database.connections.mysql.host', '127.0.0.1');
        $port = config('database.connections.mysql.port', '3306');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $passOpt = ! empty($password) ? '-p'.escapeshellarg($password) : '';
        $cmd = sprintf(
            'mysqldump -h %s --port=%s -u %s %s %s > %s 2> %s',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            $passOpt,
            escapeshellarg($database),
            escapeshellarg($outputPath),
            escapeshellarg($outputPath.'.err')
        );

        exec($cmd, $output, $returnCode);

        if (file_exists($outputPath.'.err')) {
            unlink($outputPath.'.err');
        }

        return $returnCode === 0 && file_exists($outputPath) && filesize($outputPath) > 0;
    }

    /**
     * Pure PHP MySQL dump — writes schema and data via chunked selects.
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
            $tableName = $tableObj->$dbNameKey ?? current((array) $tableObj);

            fwrite($handle, "-- -----------------------------------------------------\n");
            fwrite($handle, "-- Struktur untuk tabel: `{$tableName}`\n");
            fwrite($handle, "-- -----------------------------------------------------\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

            $createTableObj = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $createTableSql = $createTableObj[0]->{'Create Table'} ?? '';
            fwrite($handle, $createTableSql.";\n\n");

            fwrite($handle, "-- Dumping data untuk tabel: `{$tableName}`\n");

            // Get first column for stable ordering (required by Laravel chunk())
            $firstColumn = null;
            try {
                $columnsQuery = DB::select("SHOW COLUMNS FROM `{$tableName}`");
                $firstColumn = $columnsQuery[0]->Field ?? null;
            } catch (Exception) {
                // Ignore; chunk without ordering
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
                            $escaped = str_replace(['\\', "'", "\n", "\r"], ['\\\\', "\\'", '\\n', '\\r'], $val);
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
