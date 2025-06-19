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
        Schema::create('payment_receiveds', function (Blueprint $table) {
            $table->id();
            $table->string('type',20)->nullable();
            $table->integer('type_id')->nullable();
            $table->double('amount')->nullable();
            $table->string('payment_mode',20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_receiveds');
    }
};
