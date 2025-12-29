<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n" . str_repeat("=", 80) . "\n";
echo "ADMIN ACCOUNTS VERIFICATION\n";
echo str_repeat("=", 80) . "\n\n";

// Regional Admins
echo "REGIONAL ADMINS:\n";
echo str_repeat("-", 80) . "\n";
$regionalAdmins = DB::table('admins')
    ->where('role', 'region_admin')
    ->select('adminId', 'name', 'email', 'region', 'status')
    ->get();

foreach ($regionalAdmins as $admin) {
    echo sprintf("%-15s | %-25s | %-25s | %-15s | %s\n", 
        $admin->adminId, 
        $admin->name, 
        $admin->email, 
        $admin->region ?? 'N/A',
        $admin->status
    );
}

echo "\nTotal Regional Admins: " . $regionalAdmins->count() . "\n\n";

// RTOM Admins
echo "RTOM ADMINS:\n";
echo str_repeat("-", 80) . "\n";
$rtomAdmins = DB::table('admins')
    ->where('role', 'rtom_admin')
    ->select('adminId', 'name', 'email', 'region', 'rtom', 'status')
    ->get();

foreach ($rtomAdmins as $admin) {
    echo sprintf("%-15s | %-25s | %-25s | %-15s | %-5s | %s\n", 
        $admin->adminId, 
        $admin->name, 
        $admin->email, 
        $admin->region ?? 'N/A',
        $admin->rtom ?? 'N/A',
        $admin->status
    );
}

echo "\nTotal RTOM Admins: " . $rtomAdmins->count() . "\n\n";
echo str_repeat("=", 80) . "\n";
