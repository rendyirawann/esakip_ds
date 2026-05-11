<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$roles = DB::table('refrole')->get();
print_r($roles);

// Also check the user who is logged in? 
// Since I'm running from CLI, Auth::user() might be null.
// I'll check the users table for typical superadmin roles.
$superadmins = DB::table('users')->where('username', 'admin')->get();
print_r($superadmins);
