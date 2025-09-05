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
        Schema::create('admit_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->nullable();
            $table->string('admit_id',30)->nullable();
            $table->string('type',11)->nullable();
            $table->string('current_status',20)->nullable();
            $table->dateTime('discharge_date')->nullable();
            $table->string('desc',150)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admit_lists');
    }
};
