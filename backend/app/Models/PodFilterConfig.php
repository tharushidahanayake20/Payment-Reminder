<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodFilterConfig extends Model
{
    protected $fillable = [
        'bill_min',
        'bill_max',
        'call_center_staff_limit',
        'cc_limit',
        'staff_limit',
        'updated_by'
    ];

    protected $casts = [
        'bill_min' => 'integer',
        'bill_max' => 'integer',
        'call_center_staff_limit' => 'integer',
        'cc_limit' => 'integer',
        'staff_limit' => 'integer',
    ];

    /**
     * Get the admin who last updated this configuration
     */
    public function updatedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    /**
     * Get the current active configuration (singleton pattern)
     */
    public static function getConfig()
    {
        $config = self::first();

        if (!$config) {
            // Create default configuration if none exists
            $config = self::create([
                'bill_min' => 3000,
                'bill_max' => 10000,
                'call_center_staff_limit' => 30000,
                'cc_limit' => 5000,
                'staff_limit' => 3000,
            ]);
        }

        return $config;
    }

    /**
     * Validation rules for configuration updates
     */
    public static function validationRules()
    {
        return [
            'bill_min' => 'required|integer|min:0',
            'bill_max' => 'required|integer|gt:bill_min',
            'call_center_staff_limit' => 'required|integer|min:1',
            'cc_limit' => 'required|integer|min:1',
            'staff_limit' => 'required|integer|min:1',
        ];
    }
}
