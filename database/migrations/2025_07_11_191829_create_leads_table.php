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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable();
            $table->string('mobile',20)->nullable();
            $table->string('source',200)->nullable();
            $table->text('address')->nullable();
            $table->string('city',100)->nullable();
            $table->string('state',50)->nullable();
            $table->integer('pin')->nullable();
            $table->string('team',50)->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
