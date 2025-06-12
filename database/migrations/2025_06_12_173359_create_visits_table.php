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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('type',30)->nullable();
            $table->integer('patient_id')->nullable();
            $table->string('symptoms',100)->nullable();
            $table->text('previous_med_issue')->nullable();
            $table->string('appointment_date',30)->nullable();
            $table->string('old_patient',11)->nullable();
            $table->integer('consult_doctor')->nullable();
            $table->double('charge')->nullable();
            $table->double('discount')->nullable();
            $table->double('tax_per')->nullable();
            $table->double('amount')->nullable();
            $table->string('payment_mode',20)->nullable();
            $table->string('ref_num',100)->nullable();
            $table->double('paid_amount')->nullable();
            $table->text('note')->nullable();
            $table->string('status',10)->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
