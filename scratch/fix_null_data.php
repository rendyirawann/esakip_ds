<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::beginTransaction();

    // Fix Indikator Program
    echo "Fixing sakip_indikatorcascadingprogram...\n";
    DB::statement("
        UPDATE sakip_indikatorcascadingprogram i
        SET refindikatorsasaranrenstra_id = c.refindikatorsasaranrenstra_id
        FROM sakip_cascadingprogram c
        WHERE i.refcascadingprogram_id = c.refcascadingprogram_id
        AND i.refindikatorsasaranrenstra_id IS NULL
    ");

    // Fix Triwulan Program
    echo "Fixing sakip_indikatorcascadingprogram_triwulan...\n";
    DB::statement("
        UPDATE sakip_indikatorcascadingprogram_triwulan t
        SET refindikatorsasaranrenstra_id = c.refindikatorsasaranrenstra_id
        FROM sakip_cascadingprogram c
        WHERE t.refcascadingprogram_id = c.refcascadingprogram_id
        AND t.refindikatorsasaranrenstra_id IS NULL
    ");

    DB::commit();
    echo "Done! Data fixed.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
