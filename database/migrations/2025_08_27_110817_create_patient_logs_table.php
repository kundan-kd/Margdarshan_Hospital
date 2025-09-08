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
         Schema::create('patient_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->nullable();
            $table->string('admit_id',30)->nullable();
            $table->string('type',22)->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('bed_id')->nullable();
            $table->integer('doctor_id')->nullable();
            $table->date('appointment_date')->nullable();
            $table->integer('room_id')->nullable();
            $table->double('fee')->default(0);
            $table->double('paid_amount')->default(0);
            $table->string('reference_person',100)->nullable();
            $table->string('status',30)->nullable();
            $table->string('appointment_cancel_reason',200)->nullable();
            $table->string('current_status',30)->nullable();
            $table->dateTime('discharge_date')->nullable();
            $table->text('description',20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_logs');
    }
};
