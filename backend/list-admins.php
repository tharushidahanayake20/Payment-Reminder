<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$admins = DB::table('admins')->select('id', 'name', 'email', 'role')->get();

echo "Admins in database:\n";
echo "==================\n";
foreach ($admins as $admin) {
    echo "ID: {$admin->id}, Name: {$admin->name}, Email: {$admin->email}, Role: {$admin->role}\n";
}
