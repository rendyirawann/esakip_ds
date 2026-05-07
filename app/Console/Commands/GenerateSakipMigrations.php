<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateSakipMigrations extends Command
{
    protected $signature = 'generate:sakip-migrations';
    protected $description = 'Otomatis generate file migrasi Laravel dari tabel sakip_ di MySQL (Robust Version)';

    public function handle()
    {
        $this->info("Mengambil daftar tabel dari MySQL...");
        $tables = collect(DB::connection('yii_mysql')->getSchemaBuilder()->getTables())
                    ->map(fn($t) => $t['name'])
                    ->filter(fn($t) => str_starts_with($t, 'sakip_'))
                    ->unique() // Pastikan tidak ada duplikat
                    ->values()
                    ->toArray();

        $this->info("Ditemukan " . count($tables) . " tabel unik. Memulai pembuatan migrasi...");

        foreach ($tables as $index => $tableName) {
            $this->warn("Memproses skema tabel: {$tableName}");
            
            $columns = DB::connection('yii_mysql')->getSchemaBuilder()->getColumns($tableName);
            $indexes = DB::connection('yii_mysql')->getSchemaBuilder()->getIndexes($tableName);
            
            // Cari Primary Key
            $primaryKey = collect($indexes)->firstWhere('primary', true)['columns'][0] ?? null;

            $migrationName = "create_{$tableName}_table";
            $timestamp = now()->addSeconds($index)->format('Y_m_d_His');
            $fileName = "{$timestamp}_{$migrationName}.php";
            $filePath = database_path("migrations/{$fileName}");

            $columnDefinitions = "";
            foreach ($columns as $column) {
                $name = $column['name'];
                $type = strtolower($column['type_name']);
                
                if (in_array($name, ['created_at', 'updated_at'])) continue;

                // Jika kolom ini adalah primary key
                if ($name === $primaryKey) {
                    if (str_contains($type, 'int')) {
                        // Gunakan bigIncrements agar auto-increment di PostgreSQL
                        $columnDefinitions .= "            \$table->bigIncrements('{$name}');\n";
                    } else {
                        $columnDefinitions .= "            \$table->text('{$name}')->primary();\n";
                    }
                    continue;
                }

                // Jika kolom berakhiran _id
                if (str_ends_with($name, '_id')) {
                    if (str_contains($type, 'int')) {
                        $columnDefinitions .= "            \$table->bigInteger('{$name}')->nullable();\n";
                    } else {
                        $columnDefinitions .= "            \$table->text('{$name}')->nullable();\n";
                    }
                    continue;
                }

                $laravelMethod = $this->mapType($type);
                $nullable = $column['nullable'] ? '->nullable()' : '';
                
                if ($laravelMethod === 'decimal') {
                    // Paksa precision 20 agar bisa menampung angka miliaran/triliunan
                    $columnDefinitions .= "            \$table->decimal('{$name}', 20, 2){$nullable};\n";
                } else {
                    $columnDefinitions .= "            \$table->{$laravelMethod}('{$name}'){$nullable};\n";
                }
            }

            $template = "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        Schema::create('{$tableName}', function (Blueprint \$table) {\n{$columnDefinitions}            \$table->timestamps();\n        });\n    }\n\n    public function down(): void\n    {\n        Schema::dropIfExists('{$tableName}');\n    }\n};";

            File::put($filePath, $template);
        }

        $this->info("\nSelesai! " . count($tables) . " file migrasi telah dibuat.");
    }

    private function mapType($type)
    {
        $map = [
            'int' => 'integer', 'bigint' => 'bigInteger', 'tinyint' => 'boolean', 'smallint' => 'smallInteger',
            'varchar' => 'text', // Ubah dari string ke text agar tidak kena limit 255
            'text' => 'text', 'longtext' => 'longText', 'mediumtext' => 'mediumText',
            'datetime' => 'dateTime', 'timestamp' => 'timestamp', 'date' => 'date', 'decimal' => 'decimal',
            'float' => 'float', 'double' => 'double', 'char' => 'char', 'blob' => 'binary',
        ];
        foreach ($map as $key => $method) {
            if (str_contains($type, $key)) return $method;
        }
        return 'string';
    }
}
