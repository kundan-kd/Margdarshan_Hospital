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
            $table->string('type',20)->default('OPD');
            $table->dateTime('type_change_date')->nullable();
            $table->integer('patient_id')->nullable();
            $table->string('patient_name',150)->nullable();
            $table->string('token',30)->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('doctor_id')->nullable();
            $table->string('appointment_date',20)->nullable();
            $table->string('payment_mode',20)->nullable();
            $table->string('room_number',10)->nullable();
            $table->double('fee')->nullable();
            $table->double('paid_amount')->default(0);
            $table->integer('bed_id')->nullable();
            $table->string('paid_status',10)->default('UnPaid');
            $table->text('reason_for_delete')->nullable();
            $table->string('status')->default('pending');
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
