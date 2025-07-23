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
        Schema::create('library_members', function (Blueprint $table) {
            $table->id(); // MemberID as primary key
            $table->string('library_id'); // IID varchar(40)
            $table->unsignedBigInteger('user_id'); // studentID int
            $table->string('name', 60); // name varchar(60)
            $table->string('email', 40)->nullable(); // email varchar(40), nullable
            $table->tinyText('phone')->nullable(); // phone tinytext, nullable
            $table->string('fee', 20)->nullable(); // library_balance varchar(20), nullable
            $table->date('library_join_date'); // library_join_date date
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('email');
        });

        // Foreign key constraint
        Schema::table('library_members', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraint first
        Schema::table('lmembers', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });
        Schema::dropIfExists('lmembers');
    }
};
