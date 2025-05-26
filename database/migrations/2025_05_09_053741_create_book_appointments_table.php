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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->nullable();
            $table->string('patient_name',150)->nullable();
            $table->string('token',30)->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('doctor_id')->nullable();
            $table->string('appointment_date',20)->nullable();
            $table->string('payment_mode',20)->nullable();
            $table->string('room_number',10)->nullable();
            $table->double('fee')->nullable();
            $table->string('status',10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
