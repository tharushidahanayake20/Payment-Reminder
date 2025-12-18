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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('accountNumber')->unique();
            $table->string('name');
            $table->string('contactPerson')->nullable();
            $table->string('contactPersonPhone')->nullable();
            $table->string('phone')->nullable();
            $table->string('region')->nullable();
            $table->enum('rtom', ['Colombo', 'Matara', 'Negombo', 'Kandy', 'Kalutara']);
            $table->text('address')->nullable();
            $table->string('additionalInfo')->nullable();
            $table->decimal('amountOverdue', 15, 2)->default(0);
            $table->integer('daysOverdue')->default(0);
            $table->enum('status', ['active', 'paid', 'overdue', 'contacted'])->default('overdue');
            $table->foreignId('assigned_to')->nullable()->constrained('callers')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['rtom', 'status']);
            $table->index('accountNumber');
            $table->index('assigned_to');
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
