<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MigrateSakipData extends Command
{
    protected $signature = 'migrate:sakip-data';
    protected $description = 'Migrasi otomatis semua tabel SAKIP dari Yii2 ke Laravel 12 (BigInt Version)';

    public function handle()
    {
        $this->info("Mengambil daftar tabel dari MySQL...");
        $tables = collect(DB::connection('yii_mysql')->getSchemaBuilder()->getTables())
                    ->map(fn($t) => $t['name'])
                    ->filter(fn($t) => str_starts_with($t, 'sakip_'))
                    ->unique()
                    ->values()
                    ->toArray();

        $this->info("Ditemukan " . count($tables) . " tabel sakip_. Memulai migrasi data...");

        foreach ($tables as $tableName) {
            $this->warn("\nMemproses tabel: {$tableName}");
            
            // Dapatkan Primary Key
            $indexes = DB::connection('yii_mysql')->getSchemaBuilder()->getIndexes($tableName);
            $primaryKey = collect($indexes)->firstWhere('primary', true)['columns'][0] ?? 'id';

            // Cek jumlah data
            $totalRows = DB::connection('yii_mysql')->table($tableName)->count();
            if ($totalRows === 0) {
                $this->line("Tabel kosong, di-skip.");
                continue;
            }

            $bar = $this->output->createProgressBar($totalRows);
            $bar->start();

            // Truncate pgsql
            DB::connection('pgsql')->table($tableName)->truncate();

            // Chunking
            DB::connection('yii_mysql')->table($tableName)->orderBy($primaryKey)->chunk(500, function ($rows) use ($tableName, $bar, $primaryKey) {
                $insertData = [];

                foreach ($rows as $row) {
                    $data = (array) $row;

                    // 1. Timestamps
                    foreach (['created_at', 'updated_at'] as $tsCol) {
                        if (isset($data[$tsCol])) {
                            if (is_numeric($data[$tsCol])) {
                                $data[$tsCol] = Carbon::createFromTimestamp($data[$tsCol])->toDateTimeString();
                            }
                        } else {
                            $data[$tsCol] = now();
                        }
                    }

                    // 2. Data Cleaning untuk ID (Ubah string kosong ke NULL)
                    foreach ($data as $column => $value) {
                        if ($column === $primaryKey || str_ends_with($column, '_id')) {
                            if ($value === "" || $value === null) {
                                $data[$column] = null;
                            }
                        }
                    }

                    $insertData[] = $data;
                    $bar->advance();
                }

                DB::connection('pgsql')->table($tableName)->insert($insertData);
            });

            $bar->finish();
            $this->newLine();

            // Reset sequence agar auto-increment lancar di PostgreSQL
            $this->resetPostgresSequence($tableName, $primaryKey);
        }

        $this->info("\nSemua data SAKIP berhasil dimigrasi (BigInt Version)!");
    }

    private function resetPostgresSequence($tableName, $primaryKey)
    {
        try {
            $columnType = DB::connection('pgsql')->getSchemaBuilder()->getColumnType($tableName, $primaryKey);
            if (in_array($columnType, ['integer', 'bigint'])) {
                $maxId = DB::connection('pgsql')->table($tableName)->max($primaryKey) ?? 0;
                $nextId = $maxId + 1;
                
                // Cek nama sequence (biasanya tabel_kolom_seq)
                DB::connection('pgsql')->statement("SELECT setval(pg_get_serial_sequence('{$tableName}', '{$primaryKey}'), {$nextId}, false)");
                $this->line(" [OK] Sequence direset ke {$nextId}");
            }
        } catch (\Exception $e) {
            // Mungkin bukan auto-increment atau kolom tidak ada
        }
    }
}
