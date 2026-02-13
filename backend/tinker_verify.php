use App\Models\Admin;
use App\Models\FilteredCustomer;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "Starting verification...\n";

// 1. Setup Test Customer
$c = FilteredCustomer::updateOrCreate(
['ACCOUNT_NUM' => 'TC_123'],
['CUSTOMER_NAME' => 'Verify Deletion User', 'REGION' => 'GP', 'RTOM' => 'GP', 'status' => 'pending']
);
echo "Test Customer ID: " . $c->id . "\n";

// 2. Test Authorized Deletion (GP Supervisor for GP Customer)
$s = new Admin(['role' => 'supervisor', 'rtom' => 'GP']);
Auth::login($s);
$r = Request::create("/api/customers/{$c->id}", 'DELETE');
$r->setUserResolver(fn() => $s);
$res = (new CustomerController())->destroy($r, $c->id);
$data = $res->getData();
echo "Authorized Check: " . ($data->success ? "PASS" : "FAIL (" . $data->message . ")") . "\n";

// 3. Setup Second Customer for Unauthorized test
$c2 = FilteredCustomer::updateOrCreate(
['ACCOUNT_NUM' => 'TC_456'],
['CUSTOMER_NAME' => 'Verify Deletion User 2', 'REGION' => 'GP', 'RTOM' => 'GP', 'status' => 'pending']
);

// 4. Test Unauthorized Deletion (MT Supervisor for GP Customer)
$ws = new Admin(['role' => 'supervisor', 'rtom' => 'MT']);
Auth::login($ws);
$r2 = Request::create("/api/customers/{$c2->id}", 'DELETE');
$r2->setUserResolver(fn() => $ws);
$res2 = (new CustomerController())->destroy($r2, $c2->id);
$data2 = $res2->getData();
echo "Unauthorized Check (Wrong RTOM): " . (!$data2->success && strpos($data2->message, 'Access denied') !== false ?
"PASS" : "FAIL (" . $data2->message . ")") . "\n";

// Cleanup
FilteredCustomer::whereIn('ACCOUNT_NUM', ['TC_123', 'TC_456'])->delete();
echo "Cleanup complete.\n";