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
        Schema::create('lab_investigations', function (Blueprint $table) {
          $table->id();
            $table->string('type',30)->nullable();
            $table->integer('patient_id')->nullable();
            $table->integer('test_type_id')->nullable();
            $table->integer('test_name_id')->nullable();
            $table->double('amount')->default(0);
            $table->string('method',100)->nullable();
            $table->integer('report_days')->nullable();
            $table->string('test_parameter',100)->nullable();
            $table->string('test_ref_range',100)->nullable();
            $table->string('test_unit',100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_investigations');
    }
};
