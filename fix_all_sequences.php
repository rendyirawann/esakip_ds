<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Starting Global Sequence Reset for sakip_* tables...\n";

// Get all tables
$tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_name LIKE 'sakip_%'");

foreach ($tables as $t) {
    $tableName = $t->table_name;
    
    // Get the primary key (assuming it has a default sequence)
    // We try to find columns that end with '_id' or look like PK
    $columns = Schema::getColumnListing($tableName);
    $pk = null;
    
    // Pattern heuristic for this project: ref[tablename]_id
    $shortName = str_replace('sakip_', '', $tableName);
    $expectedPk = 'ref' . $shortName . '_id';
    
    if (in_array($expectedPk, $columns)) {
        $pk = $expectedPk;
    } else {
        // Fallback: first column often PK
        $pk = $columns[0];
    }

    if ($pk) {
        $sequenceName = "{$tableName}_{$pk}_seq";
        
        try {
            // Check if sequence exists
            $seqExists = DB::select("SELECT 1 FROM pg_class WHERE relkind = 'S' AND relname = ?", [$sequenceName]);
            
            if ($seqExists) {
                DB::statement("SELECT setval(?, (SELECT MAX({$pk}) FROM {$tableName}))", [$sequenceName]);
                echo "[SUCCESS] Reset sequence for {$tableName} ({$pk})\n";
            } else {
                echo "[SKIP] Sequence {$sequenceName} not found.\n";
            }
        } catch (\Exception $e) {
            echo "[ERROR] Failed to reset {$tableName}: " . $e->getMessage() . "\n";
        }
    }
}

echo "Global Sequence Reset Completed.\n";
