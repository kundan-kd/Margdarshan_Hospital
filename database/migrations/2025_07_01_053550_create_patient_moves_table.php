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
        Schema::create('patient_moves', function (Blueprint $table) {
            $table->id();
            $table->string('type',20)->nullable();
            $table->integer('patient_id')->nullable();
            $table->integer('from_bed')->nullable();
            $table->integer('to_bed')->nullable();
            $table->integer('days')->nullable();
            $table->double('charge')->nullable();
            $table->text('desc')->nullable();
            $table->string('move_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_moves');
    }
};
