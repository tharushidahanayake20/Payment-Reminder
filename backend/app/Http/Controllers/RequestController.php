<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as TaskRequest;
use App\Models\Caller;
use App\Models\Customer;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->attributes->get('user');
        $tokenData = $request->attributes->get('token_data');
        
        $query = TaskRequest::with('caller');
        
        if ($tokenData->userType === 'caller') {
            $query->where('caller_id', $user->id);
        } elseif ($tokenData->userType === 'admin' && $tokenData->role !== 'superadmin') {
            $query->whereHas('caller', function ($q) use ($user) {
                $q->where('rtom', $user->rtom);
            });
        }
        
        return response()->json($query->orderBy('sent_date', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'caller_id' => 'required|exists:callers,id',
            'customer_ids' => 'required|array'
        ]);
        
        $caller = Caller::findOrFail($validated['caller_id']);
        
        $taskRequest = TaskRequest::create([
            'task_id' => 'TASK-' . time() . '-' . rand(1000, 9999),
            'caller_id' => $caller->id,
            'caller_name' => $caller->name,
            'customers_sent' => count($validated['customer_ids']),
            'sent_date' => now(),
            'status' => 'PENDING',
            'customer_ids' => $validated['customer_ids']
        ]);
        
        // Assign customers to caller
        Customer::whereIn('id', $validated['customer_ids'])
            ->update(['assigned_to' => $caller->id]);
        
        return response()->json($taskRequest, 201);
    }

    public function show($id)
    {
        return response()->json(TaskRequest::with('caller')->findOrFail($id));
    }

    public function accept(Request $request, $id)
    {
        $taskRequest = TaskRequest::findOrFail($id);
        $taskRequest->update(['status' => 'ACCEPTED']);
        
        // Update caller's currentLoad
        $caller = $taskRequest->caller;
        $activeRequests = TaskRequest::where('caller_id', $caller->id)
            ->where('status', 'ACCEPTED')
            ->get();
        
        $caller->currentLoad = $activeRequests->sum('customers_sent');
        $caller->taskStatus = 'busy';
        $caller->save();
        
        return response()->json($taskRequest);
    }

    public function decline(Request $request, $id)
    {
        $taskRequest = TaskRequest::findOrFail($id);
        $taskRequest->update(['status' => 'DECLINED']);
        
        // Unassign customers
        Customer::whereIn('id', $taskRequest->customer_ids)
            ->update(['assigned_to' => null]);
        
        return response()->json($taskRequest);
    }

    public function update(Request $request, $id)
    {
        $taskRequest = TaskRequest::findOrFail($id);
        $taskRequest->update($request->all());
        return response()->json($taskRequest);
    }
}
