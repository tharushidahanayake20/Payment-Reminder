<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign key constraints first
        Schema::table('contact_history', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });
        
        // Drop old customers table
        Schema::dropIfExists('customers');
        
        // Create new customers table with ALL Excel columns (raw data only, no tracking)
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            
            // Excel columns - exact names
            $table->date('RUN_DATE')->nullable();
            $table->string('REGION')->nullable()->index();
            $table->string('RTOM')->nullable()->index();
            $table->string('CUSTOMER_REF')->nullable();
            $table->string('ACCOUNT_NUM')->unique();
            $table->string('PRODUCT_LABEL')->nullable();
            $table->string('MEDIUM')->nullable();
            $table->string('CUSTOMER_SEGMENT')->nullable();
            $table->string('ADDRESS_NAME')->nullable();
            $table->text('FULL_ADDRESS')->nullable();
            $table->decimal('LATEST_BILL_MNY', 15, 2)->nullable();
            $table->decimal('NEW_ARREARS', 15, 2)->nullable();
            $table->string('MOBILE_CONTACT_TEL')->nullable();
            $table->string('EMAIL_ADDRESS')->nullable();
            $table->string('CREDIT_SCORE')->nullable();
            $table->string('CREDIT_CLASS_NAME')->nullable();
            $table->string('BILL_HANDLING_CODE_NAME')->nullable();
            $table->integer('AGE_MONTHS')->nullable();
            $table->string('SALES_PERSON')->nullable();
            $table->string('ACCOUNT_MANAGER')->nullable();
            $table->string('SLT_GL_SUB_SEGMENT')->nullable();
            $table->string('BILLING_CENTRE')->nullable();
            $table->string('PROVINCE')->nullable();
            $table->string('NEXT_BILL_DTM')->nullable();
            $table->string('BILL_MONTH')->nullable();
            $table->string('LATEST_BILL_DTM')->nullable();
            $table->string('INVOICING_CO_ID')->nullable();
            $table->string('INVOICING_CO_NAME')->nullable();
            $table->string('PRODUCT_SEQ')->nullable();
            $table->string('PRODUCT_ID')->nullable();
            $table->string('LATEST_PRODUCT_STATUS')->nullable();
            $table->string('BILL_HANDLING_CODE')->nullable();
            $table->string('SLT_BUSINESS_LINE_VALUE')->nullable();
            $table->string('SALES_CHANNEL')->nullable();
            
            $table->timestamps();
            
            $table->index(['REGION', 'RTOM']);
            $table->index(['NEW_ARREARS']);
        });
        
        // Re-add foreign key constraint to contact_history
        Schema::table('contact_history', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
