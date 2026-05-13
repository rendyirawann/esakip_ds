<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = [
    'sakip_indikatorprogram', 'sakip_indikatorprogram_triwulan',
    'sakip_indikatorkegiatan', 'sakip_indikatorkegiatan_triwulan',
    'sakip_indikatorsubkegiatan', 'sakip_indikatorsubkegiatan_triwulan'
];

foreach ($tables as $t) {
    if (!Schema::connection('pgsql')->hasTable($t)) {
        echo "Table $t does not exist.\n\n";
        continue;
    }
    $cols = Schema::connection('pgsql')->getColumnListing($t);
    echo "=== $t ===\n";
    foreach ($cols as $c) {
        if (str_contains($c, 'realisasi') || str_contains($c, 'capaian') || str_contains($c, 'target') || str_contains($c, 'analisis') || str_contains($c, 'keterangan') || str_contains($c, 'id')) {
            echo "  >> $c\n";
        }
    }
    echo "\n";
}
