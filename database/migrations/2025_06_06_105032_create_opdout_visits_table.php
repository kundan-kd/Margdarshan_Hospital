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
        Schema::create('opdout_visits', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->nullable();
            // $table->string('symptomType',50)->nullable();
            $table->string('symptoms',100)->nullable();
            // $table->text('symptomDesc')->nullable();
            $table->text('previousMedIssue')->nullable();
            $table->text('note')->nullable();
            $table->string('admissionDate',40)->nullable();
            $table->text('cases')->nullable();
            $table->string('casuality',11)->nullable();
            $table->string('oldPatient',11)->nullable();
            $table->integer('consultDoctor')->nullable();
            $table->string('reference',100)->nullable();
            $table->integer('chargeCategory')->nullable();
            $table->double('charge')->nullable();
            $table->double('discount')->nullable();
            $table->double('taxPer')->nullable();
            $table->double('amount')->nullable();
            $table->string('paymentMode',20)->nullable();
            $table->string('refNum',50)->nullable();
            $table->double('paidAmount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opdout_visits');
    }
};
