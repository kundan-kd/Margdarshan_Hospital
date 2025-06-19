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
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->string('bed_no',50)->nullable();
            $table->integer('bed_type_id')->nullable();
            $table->integer('bed_group_id')->nullable();
            $table->string('floor',10)->nullable();
            $table->integer('occupied_by_patient_id')->nullable();
            $table->string('current_status',20)->default('Vacant');
            $table->string('previous_status',20)->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};
