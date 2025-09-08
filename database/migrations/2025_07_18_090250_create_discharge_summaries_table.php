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
        Schema::create('discharge_summaries', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->nullable();
            $table->string('admit_id',30)->nullable();
            $table->string('type',11)->nullable();
            $table->integer('doctor_id')->nullable();
            $table->integer('bed_id')->nullable();
            $table->integer('admit_date')->nullable();
            $table->integer('discharge_date')->nullable();
            $table->string('patient_type',30)->nullable();
            $table->text('final_diagnosis')->nullable();
            $table->string('status',80)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discharge_summaries');
    }
};
