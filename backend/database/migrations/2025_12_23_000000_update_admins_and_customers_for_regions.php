<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update admins table with new role structure and region support
        Schema::table('admins', function (Blueprint $table) {
            // First, drop the existing enum column
            $table->dropColumn('role');
        });

        Schema::table('admins', function (Blueprint $table) {
            // Add new role column with updated roles
            $table->enum('role', ['superadmin', 'region_admin', 'rtom_admin', 'supervisor', 'admin', 'uploader'])
                ->default('admin')
                ->after('phone');
            
            // Add region column (nullable, only for region admins)
            $table->string('region')->nullable()->after('role');
            
            // Update rtom to support all RTOM codes
            $table->dropColumn('rtom');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->string('rtom')->nullable()->after('region');
            
            $table->index(['role', 'region', 'rtom']);
        });

        // Update customers table to support region
        Schema::table('customers', function (Blueprint $table) {
            // Make region column more flexible
            DB::statement('ALTER TABLE customers MODIFY COLUMN region VARCHAR(255)');
            
            // Update rtom to be string instead of enum
            $table->dropColumn('rtom');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->string('rtom')->nullable()->after('region');
            
            $table->index(['region', 'rtom']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['region']);
            $table->dropColumn('role');
            $table->dropColumn('rtom');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->enum('role', ['superadmin', 'admin', 'uploader'])->default('admin');
            $table->enum('rtom', ['Colombo', 'Matara', 'Negombo', 'Kandy', 'Kalutara'])->nullable();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('rtom');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->enum('rtom', ['Colombo', 'Matara', 'Negombo', 'Kandy', 'Kalutara']);
        });
    }
};
