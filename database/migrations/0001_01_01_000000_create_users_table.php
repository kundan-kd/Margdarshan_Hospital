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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id',20)->nullable();
            $table->string('name',100)->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('mobile', 15);
            $table->string('email')->unique();
            $table->string('fname',100)->nullable();
            $table->string('mname',100)->nullable();
            $table->string('dob',50)->nullable();
            $table->string('doj',50)->nullable();
            $table->string('pan',20)->nullable();
            $table->string('adhar',20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('plain_password', 50)->nullable();
            $table->rememberToken();
            $table->integer('bloodtype_id')->nullable();
            $table->integer('usertype_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->double('fee')->default(0);
            $table->string('room_number')->nullable();
            $table->string('specialization')->nullable();
            $table->string('experience')->nullable();
            $table->string('qualification')->nullable();
            $table->string('availability')->nullable();
            $table->string('image')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('pincode')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes(); // For deleted_at field
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
