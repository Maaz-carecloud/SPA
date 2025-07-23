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
        Schema::create('product_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_supplier_id');
            $table->unsignedBigInteger('product_warehouse_id');
            $table->string('reference_no');
            $table->date('purchase_date');
            $table->string('purchase_slip')->nullable();
            $table->text('description')->nullable();
            $table->enum('payment_status', ['pending', 'partial_paid', 'fully_paid'])
                ->default('pending')->comment('0 = pending, 1 = partial_paid, 2 = fully_paid');
            $table->enum('refund_status', ['refunded', 'not_refunded'])
                ->default('not_refunded')->comment('1 = refunded, 0 = not_refunded');
            $table->double('discount', 10, 2)->default(0.00);
            $table->double('tax', 10, 2)->default(0.00);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_supplier_id')->references('id')->on('product_suppliers')->onDelete('cascade');
            $table->foreign('product_warehouse_id')->references('id')->on('product_warehouses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_purchases', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['product_supplier_id']);
            $table->dropForeign(['product_warehouse_id']);
        });
        Schema::dropIfExists('product_purchases');
    }
};
