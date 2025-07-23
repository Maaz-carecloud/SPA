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
        Schema::create('product_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('reference_no');
            $table->date('sale_date');
            $table->string('sale_slip')->nullable();
            $table->text('description')->nullable();
            $table->enum('payment_status', ['select_payment_status', 'due', 'partial' , 'paid'])
                ->default('select_payment_status')->comment('0 = select_payment_status, 1 = due, 2 = partial, 3 = paid');
            $table->enum('refund_status', ['refunded', 'not_refunded'])
                ->default('not_refunded')->comment('1 = refunded, 0 = not_refunded');
            $table->double('discount', 10, 2)->default(0.00);
            $table->double('tax', 10, 2)->default(0.00);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_sales', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('product_sales');
    }
};
