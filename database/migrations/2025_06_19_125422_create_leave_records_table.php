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
        Schema::create('leave_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('ID of the user who requested the leave');
            $table->unsignedBigInteger('leave_type_id')->comment('ID of the leave type');
            $table->text('leave_reason')->comment('Reason for the leave');
            $table->text('attachment')->nullable()->comment('Attachment for the leave request');
            $table->boolean('status')->default(true)->comment('Status of the leave request');
            $table->date('date_from')->comment('Start date of the leave');
            $table->date('date_to')->comment('End date of the leave');
            $table->integer('total_days')->comment('Total number of leave days');
            $table->string('created_by')->comment('Name of the user who created the leave record');
            $table->string('updated_by')->nullable()->comment('Name of the user who last updated the leave record');
            $table->string('added_by')->nullable()->comment('Name of the user who created the leave record');
            $table->string('added_by_custom')->nullable()->comment('Name of the user who created the leave record');
            $table->unsignedBigInteger('class_id')->nullable()->comment('ID of the class associated with the leave record');
            $table->unsignedBigInteger('section_id')->nullable()->comment('ID of the section associated with the leave record');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');

            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['date_from', 'date_to']);
            $table->index('leave_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_records');
    }
};
