<?php

use App\Models\Admin;
use App\Models\FilteredCustomer;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->boot();

// Create/Find a test customer
$customer = FilteredCustomer::firstOrCreate(
    ['ACCOUNT_NUM' => 'TEST999'],
    [
        'CUSTOMER_NAME' => 'Test Deletion',
        'REGION' => 'GP',
        'RTOM' => 'GP',
        'status' => 'pending'
    ]
);

echo "Test Customer created: ID " . $customer->id . "\n";

// Test 1: Supervisor with correct RTOM should be allowed
$supervisor = Admin::where('role', 'supervisor')->where('rtom', 'GP')->first();
if (!$supervisor) {
    // Fail-safe: create one if not found (don't save to DB if possible, or use transaction)
    $supervisor = new Admin([
        'name' => 'Test Supervisor',
        'role' => 'supervisor',
        'rtom' => 'GP'
    ]);
}

echo "Testing deletion by Supervisor (RTOM: GP)...\n";

Auth::login($supervisor);
$request = Request::create("/api/customers/{$customer->id}", 'DELETE');
$request->setUserResolver(function () use ($supervisor) {
    return $supervisor;
});

$controller = new CustomerController();
$response = $controller->destroy($request, $customer->id);
$result = $response->getData();

if ($result->success) {
    echo "SUCCESS: Supervisor was able to delete customer.\n";
} else {
    echo "FAILED: Supervisor was denied (Message: " . ($result->message ?? 'No message') . ")\n";
}

// Test 2: Supervisor with WRONG RTOM should be denied
echo "\nTesting deletion by Supervisor with WRONG RTOM (RTOM: MT)...\n";
// Re-create customer
$customer = FilteredCustomer::firstOrCreate(
    ['ACCOUNT_NUM' => 'TEST999'],
    ['CUSTOMER_NAME' => 'Test Deletion', 'REGION' => 'GP', 'RTOM' => 'GP', 'status' => 'pending']
);

$wrongSupervisor = new Admin([
    'name' => 'Wrong Supervisor',
    'role' => 'supervisor',
    'rtom' => 'MT'
]);

Auth::login($wrongSupervisor);
$request = Request::create("/api/customers/{$customer->id}", 'DELETE');
$request->setUserResolver(function () use ($wrongSupervisor) {
    return $wrongSupervisor;
});

$response = $controller->destroy($request, $customer->id);
$result = $response->getData();

if (!$result->success && strpos($result->message, 'Access denied') !== false) {
    echo "SUCCESS: Access was correctly denied for wrong RTOM.\n";
} else {
    echo "FAILED: Expected access denied, but got: " . ($result->message ?? 'Success') . "\n";
}

// Cleanup
FilteredCustomer::where('ACCOUNT_NUM', 'TEST999')->delete();
echo "\nCleanup complete.\n";
