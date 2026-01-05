<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Admin;

echo "Creating Super Admin...\n\n";

$superAdmin = [
    'name' => 'Super Administrator',
    'email' => 'superadmin@slt.lk',
    'password' => 'SuperAdmin@123',
    'adminId' => 'ADM-SUPER',
    'phone' => '0771234567',
    'role' => 'superadmin',
    'region' => null,
    'rtom' => null,
    'status' => 'active'
];

$admin = Admin::updateOrCreate(
    ['email' => $superAdmin['email']],
    $superAdmin
);

echo "✅ Super Admin Created Successfully!\n\n";
echo "=== SUPER ADMIN DETAILS ===\n";
echo "Name: {$admin->name}\n";
echo "Email: {$admin->email}\n";
echo "Admin ID: {$admin->adminId}\n";
echo "Phone: {$admin->phone}\n";
echo "Role: {$admin->role}\n";
echo "Status: {$admin->status}\n";
echo "\n🔐 Login Credentials:\n";
echo "Email: superadmin@slt.lk\n";
echo "Password: SuperAdmin@123\n";
echo "\nSelect 'Admin' as the user type during login.\n";
