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
        Schema::create('test_names', function (Blueprint $table) {
            $table->id();
            $table->integer('test_type_id')->nullable();
            $table->string('name',100)->nullable();
            $table->string('s_name',30)->nullable();
            $table->double('amount')->default(0);
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
        Schema::dropIfExists('test_names');
    }
};
