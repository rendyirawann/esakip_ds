<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "User Columns:\n";
print_r(Schema::getColumnListing('users'));

echo "\nSample Admin User:\n";
$user = DB::table('users')->where('username', 'admin')->first();
print_r($user);
