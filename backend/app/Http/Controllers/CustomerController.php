<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FilteredCustomer;
use App\Models\ContactHistory;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Start with base query - use FilteredCustomer (working table)
            $query = FilteredCustomer::with(['assignedCaller']);
            
            // Apply role-based filtering
            if ($user instanceof Admin) {
                // If user is an admin, apply region/RTOM filtering based on role
                if (!$user->isSuperAdmin()) {
                    if ($user->isRegionAdmin() && $user->region) {
                        // Region admin sees all customers in their region
                        $query->where('REGION', $user->region);
                    } elseif (($user->isRtomAdmin() || $user->isSupervisor()) && $user->rtom) {
                        // RTOM admin and supervisor see only their RTOM
                        $query->where('RTOM', $user->rtom);
                    } elseif ($user->rtom) {
                        // Legacy admin role with RTOM
                        $query->where('RTOM', $user->rtom);
                    }
                }
                // Superadmin sees all customers (no filter)
            } else {
                // Caller sees only assigned customers
                $query->where('assigned_to', $user->id);
            }
            
            // Additional filters from request
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('region')) {
                $query->where('REGION', $request->region);
            }
            
            if ($request->has('rtom')) {
                $query->where('RTOM', $request->rtom);
            }
            
            if ($request->has('callerId')) {
                $query->where('assigned_to', $request->callerId);
            }
            
            $customers = $query->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $customers,
                'count' => $customers->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Customer index error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch customers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ACCOUNT_NUM' => 'required|unique:filtered_customers',
                'CUSTOMER_NAME' => 'required',
                'REGION' => 'nullable|string',
                'RTOM' => 'nullable|string',
                'MOBILE_CONTACT_TEL' => 'nullable|string',
                'EMAIL_ADDRESS' => 'nullable|string',
                'NEW_ARREARS' => 'nullable|numeric',
                'status' => 'nullable|string'
            ]);
            
            $customer = FilteredCustomer::create($validated);
            
            return response()->json([
                'success' => true,
                'data' => $customer,
                'message' => 'Customer created successfully'
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Customer store error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();
            $customer = FilteredCustomer::with(['assignedCaller'])->findOrFail($id);
            
            // Check if user has access to this customer
            if ($user instanceof Admin && !$user->isSuperAdmin()) {
                if ($user->isRegionAdmin() && $customer->REGION !== $user->region) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access denied'
                    ], 403);
                } elseif (($user->isRtomAdmin() || $user->isSupervisor()) && $customer->RTOM !== $user->rtom) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access denied'
                    ], 403);
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $customer
            ]);
            
        } catch (\Exception $e) {
            Log::error('Customer show error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();
            $customer = FilteredCustomer::findOrFail($id);
            
            // Check if user has access to update this customer
            if ($user instanceof Admin && !$user->isSuperAdmin()) {
                if ($user->isRegionAdmin() && $customer->REGION !== $user->region) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access denied'
                    ], 403);
                } elseif (($user->isRtomAdmin() || $user->isSupervisor()) && $customer->RTOM !== $user->rtom) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access denied'
                    ], 403);
                }
            }
            
            $customer->update($request->all());
            
            return response()->json([
                'success' => true,
                'data' => $customer,
                'message' => 'Customer updated successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Customer update error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            $customer = FilteredCustomer::findOrFail($id);
            
            // Only superadmin can delete customers
            if ($user instanceof Admin && !$user->isSuperAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only superadmin can delete customers'
                ], 403);
            }
            
            $customer->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Customer destroy error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addContactHistory(Request $request, $id)
    {
        $validated = $request->validate([
            'contact_date' => 'required|date',
            'outcome' => 'required',
            'remark' => 'nullable',
            'promised_date' => 'nullable|date',
            'payment_made' => 'boolean'
        ]);
        
        $validated['customer_id'] = $id;
        $contactHistory = ContactHistory::create($validated);
        
        FilteredCustomer::findOrFail($id)->update(['status' => 'contacted']);
        
        return response()->json($contactHistory, 201);
    }
}
