<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::statement("SELECT setval('sakip_sasaranrenstra_refsasaranrenstra_id_seq', (SELECT MAX(refsasaranrenstra_id) FROM sakip_sasaranrenstra))");
    echo "Sequence reset successful.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
