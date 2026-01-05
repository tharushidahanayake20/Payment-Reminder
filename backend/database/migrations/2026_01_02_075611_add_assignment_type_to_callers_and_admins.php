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
        // Add assignment_type to callers table
        Schema::table('callers', function (Blueprint $table) {
            $table->enum('assignment_type', ['Call Center Staff', 'CC', 'Staff', 'Billing Center'])
                ->nullable()
                ->after('rtom')
                ->comment('Center assignment: Call Center Staff, CC, Staff, or Billing Center');
        });

        // Add assignment_type to admins table (for supervisors)
        Schema::table('admins', function (Blueprint $table) {
            $table->enum('assignment_type', ['Call Center Staff', 'CC', 'Staff', 'Billing Center'])
                ->nullable()
                ->after('rtom')
                ->comment('Center assignment: Call Center Staff, CC, Staff, or Billing Center');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('callers', function (Blueprint $table) {
            $table->dropColumn('assignment_type');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('assignment_type');
        });
    }
};
