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
        Schema::table('filtered_customers', function (Blueprint $table) {
            $table->string('assignment_type')->nullable()->after('assigned_to')
                ->comment('Assignment category from POD filter: Call Center Staff, CC, Staff, or Billing Center');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filtered_customers', function (Blueprint $table) {
            $table->dropColumn('assignment_type');
        });
    }
};
