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
        Schema::create('pod_filter_configs', function (Blueprint $table) {
            $table->id();

            // Bill value range for call center assignment
            $table->integer('bill_min')->default(3000)->comment('Minimum arrears for call center assignment');
            $table->integer('bill_max')->default(10000)->comment('Maximum arrears for call center assignment');

            // Account limits for different assignment types
            $table->integer('call_center_staff_limit')->default(30000)->comment('Maximum accounts for Call Center Staff');
            $table->integer('cc_limit')->default(5000)->comment('Maximum accounts for CC');
            $table->integer('staff_limit')->default(3000)->comment('Maximum accounts for Staff');

            // Audit tracking
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Admin ID who last updated config');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pod_filter_configs');
    }
};
