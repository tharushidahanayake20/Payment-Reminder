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
        Schema::create('callers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('callerId')->unique();
            $table->string('phone')->nullable();
            $table->integer('maxLoad')->default(10);
            $table->integer('currentLoad')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('taskStatus', ['available', 'busy'])->default('available');
            $table->enum('rtom', ['Colombo', 'Matara', 'Negombo', 'Kandy', 'Kalutara']);
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['rtom', 'status']);
            $table->index('taskStatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('callers');
    }
};
