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
        Schema::create('payment_bills', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->nullable();
            $table->string('type',20)->nullable();
            $table->integer('type_id')->nullable();
            $table->string('amount_for',20)->nullable();
            $table->integer('days')->nullable();
            $table->string('title',200)->nullable();
            $table->double('amount')->default(0);
            $table->string('payment_mode',20)->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_bills');
    }
};
