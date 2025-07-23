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
        Schema::create('product_purchased_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_purchase_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('quantity', 15, 2)->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->foreign('product_purchase_id')->references('id')->on('product_purchases')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_purchased_items', function (Blueprint $table) {
            $table->dropForeign(['product_purchase_id']);
            $table->dropForeign(['product_id']);
        });
        Schema::dropIfExists('product_purchased_items');
    }
};
