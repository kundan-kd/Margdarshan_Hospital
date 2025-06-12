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
        Schema::create('opdout_labtests', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->nullable();
            $table->integer('test_type_id')->nullable();
            $table->integer('test_name_id')->nullable();
            $table->double('amount')->default(0);
            $table->string('method',100)->nullable();
            $table->integer('reportDays')->nullable();
            $table->string('testParameter',100)->nullable();
            $table->string('testRefRange',100)->nullable();
            $table->string('testUnit',100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opdout_labtests');
    }
};
