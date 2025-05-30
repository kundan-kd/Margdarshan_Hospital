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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();  
            $table->string('type',20)->nullable();
            $table->integer('patient_id')->nullable();
            $table->string('bill_no',100)->nullable();
            $table->integer('res_doctor_id')->nullable();
            $table->string('out_doctor_name',100)->nullable();
            $table->double('total_amount')->nullable();
            $table->double('discount_per')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('taxes')->nullable();
            $table->double('net_amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('due_amount')->nullable();
            $table->string('payment_mode',40)->nullable();
            $table->text('naration')->nullable();
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
        Schema::dropIfExists('billings');
    }
};
