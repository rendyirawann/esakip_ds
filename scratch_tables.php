<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = array_map(function($t) { return $t['name']; }, DB::connection('yii_mysql')->getSchemaBuilder()->getTables());
$sakipTables = array_filter($tables, function($t) { return str_starts_with($t, 'sakip_'); });

echo json_encode(array_values($sakipTables));
