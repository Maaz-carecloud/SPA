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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of the leave type (e.g., Sick Leave, Annual Leave)');
            $table->text('description')->nullable()->comment('Description of the leave type');
            $table->boolean('is_active')->default(true)->comment('Whether this leave type is active');
            $table->string('created_by')->nullable()->comment('Name of the user who created the leave type');
            $table->string('updated_by')->nullable()->comment('Name of the user who last updated the leave type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
