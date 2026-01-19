<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelUploadHistory extends Model
{
    protected $table = 'excel_upload_history';

    protected $fillable = [
        'original_filename',
        'stored_filename',
        'file_path',
        'file_size',
        'total_records',
        'processed_records',
        'uploaded_by'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'total_records' => 'integer',
        'processed_records' => 'integer',
    ];

    /**
     * Get the admin who uploaded this file
     */
    public function uploadedBy()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
