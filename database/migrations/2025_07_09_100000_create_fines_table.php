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
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained();
            $table->decimal('amount', 8, 2);
            $table->string('reason')->default('Overdue fine');
            $table->enum('status', ['pending', 'paid', 'waived'])->default('pending');
            $table->date('fine_date');
            $table->date('paid_date')->nullable();
            $table->decimal('paid_amount', 8, 2)->nullable();
            $table->text('payment_note')->nullable();
            $table->string('added_by')->nullable(); // User who added the fine
            $table->string('paid_by')->nullable(); // User who processed payment
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['issue_id', 'status']);
            $table->index(['status', 'fine_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
