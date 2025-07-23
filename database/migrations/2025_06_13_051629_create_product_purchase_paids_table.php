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
        Schema::create('product_purchase_paids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_purchase_id');
            $table->string('reference_no');
            $table->double('paid_amount')->default(0.00);
            $table->enum('payment_method', ['cash', 'cheque', 'credit_card', 'other'])->default('cash')
                ->comment('1 = cash, 2 = cheque, 3 = credit card, 4 = other');
            $table->string('paid_slip')->nullable();
            $table->text('description')->nullable();
            $table->date('payment_date');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->foreign('product_purchase_id')->references('id')->on('product_purchases')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_purchase_paids');
    }
};
