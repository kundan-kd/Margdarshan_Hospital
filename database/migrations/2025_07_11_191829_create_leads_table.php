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
            $table->integer('assign_to')->nullable();
            $table->dateTime('assign_date')->nullable();
            $table->integer('assign_by')->nullable();
            $table->integer('previous_assign_to')->nullable();
            $table->text('assign_transfer_reason')->nullable();
            $table->dateTime('assign_transfer_date')->nullable();
            $table->integer('naration_list_id')->nullable();
            $table->text('naration')->nullable();
            $table->dateTime('next_followup_date')->nullable();
            $table->string('lead_status')->default('Pending');
            $table->integer('lead_patient_id')->nullable();
            $table->dateTime('lead_status_date')->nullable();
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
