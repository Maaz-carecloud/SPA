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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('email');
            $table->enum('user_type', ['student', 'teacher', 'employee', 'parent', 'admin'])->default('student');
            $table->date('dob')->nullable()->after('user_type');
            $table->string('gender')->nullable()->after('dob');
            $table->string('religion')->nullable()->after('gender');
            $table->string('phone')->nullable()->after('religion');
            $table->string('address')->nullable()->after('phone');
            $table->string('country')->nullable()->after('address');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->string('avatar')->nullable()->comment('profile image')->after('city');
            $table->string('blood_group')->nullable()->after('avatar');
            $table->string('registration_no')->nullable()->after('blood_group');
            $table->tinyInteger('transport_status')->default(0)->after('registration_no');
            $table->unsignedBigInteger('transport_id')->nullable()->after('transport_status');
            $table->tinyInteger('is_active')->default(1)->after('transport_id');
            $table->string('created_by')->nullable()->after('is_active');
            $table->string('updated_by')->nullable()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'user_type', 'dob', 'gender', 'religion', 'phone', 
                'address', 'country', 'state', 'city', 'avatar',
                'blood_group', 'registration_no', 'transport_status', 'transport_id',
                'is_active', 'created_by', 'updated_by'
            ]);
        });
    }
};
