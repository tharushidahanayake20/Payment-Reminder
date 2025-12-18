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
        Schema::create('contact_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->dateTime('contact_date');
            $table->string('outcome');
            $table->text('remark')->nullable();
            $table->date('promised_date')->nullable();
            $table->boolean('payment_made')->default(false);
            $table->timestamps();
            
            $table->index('customer_id');
            $table->index('contact_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_history');
    }
};
