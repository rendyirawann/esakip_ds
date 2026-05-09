<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateSakipModels extends Command
{
    protected $signature = 'generate:sakip-models';
    protected $description = 'Otomatis generate Model Laravel dari tabel sakip_';

    public function handle()
    {
        $tables = collect(DB::connection()->getSchemaBuilder()->getTables())
                    ->map(fn($t) => $t['name'])
                    ->filter(fn($t) => str_starts_with($t, 'sakip_'))
                    ->values();

        $this->info("Ditemukan " . $tables->count() . " tabel sakip_.");

        foreach ($tables as $tableName) {
            $modelName = Str::studly(str_replace('sakip_', 'Sakip_', $tableName));
            $filePath = app_path("Models/{$modelName}.php");

            if (File::exists($filePath)) {
                $this->warn("Model {$modelName} sudah ada, skip.");
                continue;
            }

            // Dapatkan info kolom & index
            $columns = DB::connection()->getSchemaBuilder()->getColumns($tableName);
            $indexes = DB::connection()->getSchemaBuilder()->getIndexes($tableName);
            
            // Cari Primary Key
            $primaryKey = collect($indexes)->firstWhere('primary', true)['columns'][0] ?? 'id';
            
            // Cek apakah PK auto increment (integer) atau bukan (string/uuid)
            $pkType = collect($columns)->firstWhere('name', $primaryKey)['type_name'] ?? 'bigint';
            $isIncrementing = str_contains(strtolower($pkType), 'int');

            $relations = "";
            foreach ($columns as $column) {
                $colName = $column['name'];
                if (str_ends_with($colName, '_id') && $colName !== $primaryKey) {
                    $relatedTable = $this->guessTable($colName);
                    if ($relatedTable) {
                        $methodName = Str::camel(str_replace(['ref', '_id'], '', $colName));
                        $relatedModel = Str::studly(str_replace('sakip_', 'Sakip_', $relatedTable));
                        $relations .= "\n    public function {$methodName}()\n    {\n        return \$this->belongsTo({$relatedModel}::class, '{$colName}', '{$this->guessPrimaryKey($relatedTable)}');\n    }\n";
                    }
                }
            }

            $template = "<?php\n\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Model;\nuse Illuminate\Database\Eloquent\Factories\HasFactory;\n\nclass {$modelName} extends Model\n{\n    use HasFactory;\n\n    protected \$table = '{$tableName}';\n    protected \$primaryKey = '{$primaryKey}';\n    public \$incrementing = " . ($isIncrementing ? 'true' : 'false') . ";\n    protected \$keyType = '" . ($isIncrementing ? 'int' : 'string') . "';\n\n    protected \$guarded = [];\n{$relations}\n}\n";

            File::put($filePath, $template);
            $this->line("Dibuat: Models/{$modelName}.php");
        }

        $this->info("\nSemua Model berhasil dibuat!");
    }

    private function guessTable($colName)
    {
        $base = str_replace(['ref', '_id'], '', $colName);
        $tables = [
            'skpd' => 'sakip_skpd',
            'urusan' => 'sakip_urusan',
            'bidang' => 'sakip_bidang',
            'visi' => 'sakip_visi',
            'misi' => 'sakip_misi',
            'periode' => 'sakip_periode',
            'program' => 'sakip_program',
            'kegiatan' => 'sakip_kegiatan',
            'subkegiatan' => 'sakip_subkegiatan',
        ];
        return $tables[$base] ?? null;
    }

    private function guessPrimaryKey($tableName)
    {
        $base = str_replace('sakip_', '', $tableName);
        return "ref{$base}_id";
    }
}
