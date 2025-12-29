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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('task_id')->unique();
            $table->foreignId('caller_id')->constrained('callers')->onDelete('cascade');
            $table->string('caller_name');
            $table->integer('customers_sent')->default(0);
            $table->integer('contacted')->default(0);
            $table->dateTime('sent_date');
            $table->enum('status', ['PENDING', 'ACCEPTED', 'DECLINED'])->default('PENDING');
            $table->json('customer_ids')->nullable();
            $table->timestamps();
            
            $table->index(['caller_id', 'status']);
            $table->index('task_id');
            $table->index('sent_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
