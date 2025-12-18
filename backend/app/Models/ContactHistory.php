<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactHistory extends Model
{
    protected $table = 'contact_history';

    protected $fillable = [
        'customer_id',
        'contact_date',
        'outcome',
        'remark',
        'promised_date',
        'payment_made'
    ];

    protected $casts = [
        'contact_date' => 'datetime',
        'promised_date' => 'date',
        'payment_made' => 'boolean'
    ];

    // Relationship: Contact history belongs to customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
