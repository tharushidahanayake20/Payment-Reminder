<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Create uploader account
$inserted = DB::table('admins')->insert([
    'adminId' => 'UPLOADER001',
    'name' => 'Data Uploader',
    'email' => 'uploader@slt.lk',
    'password' => Hash::make('Upload@123'),
    'role' => 'uploader',
    'status' => 'active',
    'created_at' => now(),
    'updated_at' => now(),
]);

if ($inserted) {
    echo "Uploader account created successfully!\n";
    echo "Email: uploader@slt.lk\n";
    echo "Password: Upload@123\n";
    echo "Role: uploader\n";
}

// Create superadmin account
$inserted2 = DB::table('admins')->insert([
    'adminId' => 'SUPERADMIN001',
    'name' => 'Super Admin',
    'email' => 'superadmin@slt.lk',
    'password' => Hash::make('Admin@123'),
    'role' => 'superadmin',
    'status' => 'active',
    'created_at' => now(),
    'updated_at' => now(),
]);

if ($inserted2) {
    echo "\nSuperadmin account created successfully!\n";
    echo "Email: superadmin@slt.lk\n";
    echo "Password: Admin@123\n";
    echo "Role: superadmin\n";
}
