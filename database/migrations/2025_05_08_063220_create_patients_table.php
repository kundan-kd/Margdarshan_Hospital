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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id',50)->nullable();
            $table->string('name',150)->nullable();
            $table->string('guardian_name',150)->nullable();
            $table->string('gender',10)->nullable();
            $table->string('bloodtype',30)->nullable();
            $table->string('dob',150)->nullable();
            $table->string('marital_status',20)->nullable();
            $table->string('mobile',30)->nullable();
            $table->string('alt_mobile',30)->nullable();
            $table->string('known_allergies',200)->nullable();
            $table->text('address')->nullable();
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
        Schema::dropIfExists('patients');
    }
};
