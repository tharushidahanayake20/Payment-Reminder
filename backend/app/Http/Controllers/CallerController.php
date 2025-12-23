<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caller;
use App\Models\Request as TaskRequest;

class CallerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->attributes->get('user');
        $tokenData = $request->attributes->get('token_data');
        
        $query = Caller::with(['creator', 'customers']);
        
        // Apply role-based filtering
        if ($tokenData->userType === 'admin') {
            if ($tokenData->role === 'superadmin') {
                // Superadmin sees all callers (no filter)
            } elseif ($tokenData->role === 'region_admin' && $user->region) {
                // Region admin sees all callers in their region
                $query->where('region', $user->region);
            } elseif (($tokenData->role === 'rtom_admin' || $tokenData->role === 'supervisor') && $user->rtom) {
                // RTOM admin and supervisor see only callers in their RTOM
                $query->where('rtom', $user->rtom);
            } elseif ($user->rtom) {
                // Legacy admin role with RTOM
                $query->where('rtom', $user->rtom);
            }
        }
        
        $callers = $query->get();
        
        // Calculate currentLoad from active requests
        foreach ($callers as $caller) {
            $activeRequests = TaskRequest::where('caller_id', $caller->id)
                ->where('status', 'ACCEPTED')
                ->get();
            
            $actualLoad = $activeRequests->sum('customers_sent');
            $caller->currentLoad = $actualLoad;
            $caller->save();
        }
        
        return response()->json($callers);
    }

    public function store(Request $request)
    {
        $user = $request->attributes->get('user');
        
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:callers',
            'password' => 'required|min:6',
            'callerId' => 'required|unique:callers',
            'phone' => 'nullable',
            'maxLoad' => 'required|integer|min:1|max:100'
        ]);
        
        // Auto-assign region and RTOM from authenticated admin
        $validated['region'] = $user->region;
        $validated['rtom'] = $user->rtom;
        $validated['created_by'] = $user->id;
        
        return response()->json(Caller::create($validated), 201);
    }

    public function show($id)
    {
        return response()->json(Caller::with(['customers', 'requests'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $caller = Caller::findOrFail($id);
        $caller->update($request->except(['region', 'rtom', 'created_by']));
        return response()->json($caller);
    }

    public function destroy($id)
    {
        Caller::findOrFail($id)->delete();
        return response()->json(['message' => 'Caller deleted']);
    }
}
