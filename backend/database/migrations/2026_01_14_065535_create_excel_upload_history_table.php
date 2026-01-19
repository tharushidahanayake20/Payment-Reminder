<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('excel_upload_history', function (Blueprint $table) {
            $table->id();

            // File information
            $table->string('original_filename')->comment('Original name of uploaded file');
            $table->string('stored_filename')->comment('Stored filename on server');
            $table->string('file_path')->comment('Full path to stored file');
            $table->bigInteger('file_size')->comment('File size in bytes');

            // Processing statistics
            $table->integer('total_records')->nullable()->comment('Total records in Excel file');
            $table->integer('processed_records')->nullable()->comment('Successfully processed records');

            // Upload metadata
            $table->unsignedBigInteger('uploaded_by')->comment('Admin ID who uploaded the file');
            $table->foreign('uploaded_by')->references('id')->on('admins')->onDelete('cascade');

            $table->timestamps();

            // Index for faster queries
            $table->index('uploaded_by');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_upload_history');
    }
};
