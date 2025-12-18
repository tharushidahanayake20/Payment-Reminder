<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ContactHistory;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->attributes->get('user');
        $tokenData = $request->attributes->get('token_data');
        
        $query = Customer::with(['caller', 'contactHistory']);
        
        // RTOM filtering for non-superadmin
        if ($tokenData->userType === 'admin' && $tokenData->role !== 'superadmin') {
            $query->where('rtom', $user->rtom);
        } elseif ($tokenData->userType === 'caller') {
            $query->where('assigned_to', $user->id);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('rtom')) {
            $query->where('rtom', $request->rtom);
        }
        
        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'accountNumber' => 'required|unique:customers',
            'name' => 'required',
            'rtom' => 'required|in:Colombo,Matara,Negombo,Kandy,Kalutara'
        ]);
        
        return response()->json(Customer::create($validated), 201);
    }

    public function show($id)
    {
        return response()->json(Customer::with(['caller', 'contactHistory'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());
        return response()->json($customer);
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();
        return response()->json(['message' => 'Customer deleted']);
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
        
        Customer::findOrFail($id)->update(['status' => 'contacted']);
        
        return response()->json($contactHistory, 201);
    }
}
