<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Caller;
use App\Models\Customer;
use App\Models\Request as TaskRequest;
use App\Models\ContactHistory;

class AdminController extends Controller
{
    public function getDashboardStats(Request $request)
    {
        $user = $request->attributes->get('user');
        $tokenData = $request->attributes->get('token_data');
        
        $query = Customer::query();
        
        // RTOM filtering
        if ($tokenData->role !== 'superadmin') {
            $query->where('rtom', $user->rtom);
        }
        
        $totalCustomers = (clone $query)->count();
        $overdueCustomers = (clone $query)->where('status', 'overdue')->count();
        $contactedCustomers = (clone $query)->where('status', 'contacted')->count();
        $paidCustomers = (clone $query)->where('status', 'paid')->count();
        
        return response()->json([
            'totalCustomers' => $totalCustomers,
            'overdueCustomers' => $overdueCustomers,
            'contactedCustomers' => $contactedCustomers,
            'paidCustomers' => $paidCustomers
        ]);
    }

    public function getAssignedCallers(Request $request)
    {
        $user = $request->attributes->get('user');
        $tokenData = $request->attributes->get('token_data');
        
        $query = Caller::with('customers');
        
        if ($tokenData->role !== 'superadmin') {
            $query->where('rtom', $user->rtom);
        }
        
        return response()->json($query->get());
    }

    public function getWeeklyCalls(Request $request)
    {
        $user = $request->attributes->get('user');
        $tokenData = $request->attributes->get('token_data');
        
        $query = ContactHistory::whereBetween('contact_date', [
            now()->subWeek(),
            now()
        ]);
        
        if ($tokenData->role !== 'superadmin') {
            $query->whereHas('customer', function ($q) use ($user) {
                $q->where('rtom', $user->rtom);
            });
        }
        
        return response()->json(['count' => $query->count()]);
    }

    // Superadmin operations
    public function getAllAdmins()
    {
        return response()->json(Admin::all());
    }

    public function createAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
            'adminId' => 'required|unique:admins',
            'role' => 'required|in:superadmin,admin,uploader',
            'rtom' => 'nullable|in:Colombo,Matara,Negombo,Kandy,Kalutara'
        ]);
        
        return response()->json(Admin::create($validated), 201);
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->update($request->except(['password']));
        return response()->json($admin);
    }

    public function deleteAdmin($id)
    {
        Admin::findOrFail($id)->delete();
        return response()->json(['message' => 'Admin deleted']);
    }

    public function getRtoms()
    {
        return response()->json([
            'rtoms' => ['Colombo', 'Matara', 'Negombo', 'Kandy', 'Kalutara']
        ]);
    }
}
