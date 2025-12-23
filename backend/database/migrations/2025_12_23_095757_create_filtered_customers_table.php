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
        Schema::create('filtered_customers', function (Blueprint $table) {
            $table->id();
            
            // Essential columns from Excel for caller work
            $table->string('REGION')->nullable()->index();
            $table->string('RTOM')->nullable()->index();
            $table->string('ACCOUNT_NUM')->unique();
            $table->string('PRODUCT_LABEL')->nullable();
            $table->string('MEDIUM')->nullable();
            $table->string('CUSTOMER_NAME')->nullable();
            $table->decimal('LATEST_BILL_MNY', 15, 2)->nullable();
            $table->decimal('NEW_ARREARS', 15, 2)->nullable()->index();
            $table->string('CREDIT_SCORE')->nullable();
            $table->string('ACCOUNT_MANAGER')->nullable();
            $table->string('BILL_HANDLING_CODE_NAME')->nullable();
            $table->string('MOBILE_CONTACT_TEL')->nullable();
            $table->string('EMAIL_ADDRESS')->nullable();
            $table->string('NEXT_BILL_DATE')->nullable();
            $table->integer('AGE_MONTHS')->nullable();
            $table->string('SALES_PERSON')->nullable();
            $table->string('CREDIT_CLASS_NAME')->nullable();
            $table->text('REMARK')->nullable();
            
            // Caller working columns
            $table->date('CONTACT_DATE')->nullable();
            $table->string('CRM_ACTION')->nullable();
            $table->integer('RETRY_COUNT')->default(0)->comment('If unable to contact, how many times retried');
            $table->text('CUSTOMER_FEEDBACK')->nullable();
            $table->string('CREDIT_ACTION')->nullable();
            $table->string('MOBILE_NO_CONFIRMATION')->nullable();
            
            // System columns
            $table->enum('status', ['pending', 'contacted', 'paid', 'overdue'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('callers')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['REGION', 'RTOM']);
            $table->index(['status', 'NEW_ARREARS']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filtered_customers');
    }
};
