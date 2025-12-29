<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$email = 'uploader@slt.lk';
$password = 'Upload@123';

$updated = DB::table('admins')
    ->where('email', $email)
    ->update(['password' => Hash::make($password)]);

if ($updated) {
    echo "Password updated successfully for $email\n";
    echo "New password: $password\n";
} else {
    echo "No user found with email: $email\n";
}
