<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
echo "Indikator Sasaran:\n";
print_r(Schema::getColumnListing('sakip_indikatorsasaranrenstra'));
echo "\nIndikator Tujuan:\n";
print_r(Schema::getColumnListing('sakip_indikatortujuanrenstra'));
