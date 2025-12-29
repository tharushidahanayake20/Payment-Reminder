<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "Creating Regional and RTOM Admin Accounts...\n\n";

// Regional Admins for all regions
$regionalAdmins = [
    [
        'adminId' => 'RADM-METRO',
        'name' => 'Metro Region Admin',
        'email' => 'metro@slt.lk',
        'password' => Hash::make('Metro@123'),
        'role' => 'region_admin',
        'region' => 'Metro Region',
        'rtom' => null,
        'phone' => '0112345001',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'adminId' => 'RADM-R1',
        'name' => 'Region 1 Admin',
        'email' => 'region01@slt.lk',
        'password' => Hash::make('Region01@123'),
        'role' => 'region_admin',
        'region' => 'Region 1',
        'rtom' => null,
        'phone' => '0112345002',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'adminId' => 'RADM-R2',
        'name' => 'Region 2 Admin',
        'email' => 'region02@slt.lk',
        'password' => Hash::make('Region02@123'),
        'role' => 'region_admin',
        'region' => 'Region 2',
        'rtom' => null,
        'phone' => '0112345003',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'adminId' => 'RADM-R3',
        'name' => 'Region 3 Admin',
        'email' => 'region03@slt.lk',
        'password' => Hash::make('Region03@123'),
        'role' => 'region_admin',
        'region' => 'Region 3',
        'rtom' => null,
        'phone' => '0112345004',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

// RTOM Admins - one per region as examples
$rtomAdmins = [
    // Metro Region - Colombo
    [
        'adminId' => 'RTOM-CO',
        'name' => 'Colombo RTOM Admin',
        'email' => 'colombo@slt.lk',
        'password' => Hash::make('Colombo@123'),
        'role' => 'rtom_admin',
        'region' => 'Metro Region',
        'rtom' => 'CO',
        'phone' => '0112345101',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    // Region 1 - Kandy
    [
        'adminId' => 'RTOM-KA',
        'name' => 'Kandy RTOM Admin',
        'email' => 'kandy@slt.lk',
        'password' => Hash::make('Kandy@123'),
        'role' => 'rtom_admin',
        'region' => 'Region 1',
        'rtom' => 'KA',
        'phone' => '0112345102',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    // Region 2 - Galle
    [
        'adminId' => 'RTOM-GA',
        'name' => 'Galle RTOM Admin',
        'email' => 'galle@slt.lk',
        'password' => Hash::make('Galle@123'),
        'role' => 'rtom_admin',
        'region' => 'Region 2',
        'rtom' => 'GA',
        'phone' => '0112345103',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    // Region 3 - Jaffna
    [
        'adminId' => 'RTOM-JA',
        'name' => 'Jaffna RTOM Admin',
        'email' => 'jaffna@slt.lk',
        'password' => Hash::make('Jaffna@123'),
        'role' => 'rtom_admin',
        'region' => 'Region 3',
        'rtom' => 'JA',
        'phone' => '0112345104',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

// Insert Regional Admins
echo "Creating Regional Admins...\n";
foreach ($regionalAdmins as $admin) {
    try {
        // Check if email already exists
        $exists = DB::table('admins')->where('email', $admin['email'])->exists();
        
        if ($exists) {
            echo "  ⚠️  {$admin['email']} already exists, skipping...\n";
            continue;
        }
        
        DB::table('admins')->insert($admin);
        echo "  ✅ Created {$admin['name']} ({$admin['email']})\n";
        echo "     Password: " . str_replace(Hash::make(''), '', $admin['email']) . "\n";
    } catch (\Exception $e) {
        echo "  ❌ Failed to create {$admin['email']}: {$e->getMessage()}\n";
    }
}

echo "\nCreating RTOM Admins...\n";
foreach ($rtomAdmins as $admin) {
    try {
        // Check if email already exists
        $exists = DB::table('admins')->where('email', $admin['email'])->exists();
        
        if ($exists) {
            echo "  ⚠️  {$admin['email']} already exists, skipping...\n";
            continue;
        }
        
        DB::table('admins')->insert($admin);
        echo "  ✅ Created {$admin['name']} ({$admin['email']})\n";
    } catch (\Exception $e) {
        echo "  ❌ Failed to create {$admin['email']}: {$e->getMessage()}\n";
    }
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "CREDENTIALS SUMMARY\n";
echo str_repeat("=", 70) . "\n\n";

echo "REGIONAL ADMINS:\n";
echo str_repeat("-", 70) . "\n";
echo "Metro Region:\n";
echo "  Email: metro@slt.lk\n";
echo "  Password: Metro@123\n";
echo "  Access: All customers and callers in Metro Region\n\n";

echo "Region 1:\n";
echo "  Email: region01@slt.lk\n";
echo "  Password: Region01@123\n";
echo "  Access: All customers and callers in Region 1\n\n";

echo "Region 2:\n";
echo "  Email: region02@slt.lk\n";
echo "  Password: Region02@123\n";
echo "  Access: All customers and callers in Region 2\n\n";

echo "Region 3:\n";
echo "  Email: region03@slt.lk\n";
echo "  Password: Region03@123\n";
echo "  Access: All customers and callers in Region 3\n\n";

echo "\nRTOM ADMINS:\n";
echo str_repeat("-", 70) . "\n";
echo "Colombo (Metro Region):\n";
echo "  Email: colombo@slt.lk\n";
echo "  Password: Colombo@123\n";
echo "  Access: Only customers and callers in CO (Colombo) RTOM\n\n";

echo "Kandy (Region 1):\n";
echo "  Email: kandy@slt.lk\n";
echo "  Password: Kandy@123\n";
echo "  Access: Only customers and callers in KA (Kandy) RTOM\n\n";

echo "Galle (Region 2):\n";
echo "  Email: galle@slt.lk\n";
echo "  Password: Galle@123\n";
echo "  Access: Only customers and callers in GA (Galle) RTOM\n\n";

echo "Jaffna (Region 3):\n";
echo "  Email: jaffna@slt.lk\n";
echo "  Password: Jaffna@123\n";
echo "  Access: Only customers and callers in JA (Jaffna) RTOM\n\n";

echo str_repeat("=", 70) . "\n";
echo "Done! Total accounts created/checked: " . (count($regionalAdmins) + count($rtomAdmins)) . "\n";
echo str_repeat("=", 70) . "\n";
