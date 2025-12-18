<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'accountNumber',
        'name',
        'contactPerson',
        'contactPersonPhone',
        'phone',
        'region',
        'rtom',
        'address',
        'additionalInfo',
        'amountOverdue',
        'daysOverdue',
        'status',
        'assigned_to'
    ];

    protected $casts = [
        'amountOverdue' => 'decimal:2',
        'daysOverdue' => 'integer',
        'status' => 'string',
        'rtom' => 'string'
    ];

    // Relationship: Customer assigned to caller
    public function caller()
    {
        return $this->belongsTo(Caller::class, 'assigned_to');
    }

    // Relationship: Customer has many contact history records
    public function contactHistory()
    {
        return $this->hasMany(ContactHistory::class, 'customer_id')->orderBy('contact_date', 'desc');
    }

    // Get latest contact
    public function latestContact()
    {
        return $this->contactHistory()->latest('contact_date')->first();
    }
}
