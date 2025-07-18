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
        Schema::create('discharge_summaries', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->nullable();
            $table->text('final_diagnosis')->nullable();
            $table->text('chief_complaint')->nullable();
            $table->text('past_history')->nullable();
            $table->text('clinical_finding')->nullable();
            $table->text('investigation')->nullable();
            $table->text('brief_history')->nullable();
            $table->text('condition_at_discharge')->nullable();
            $table->text('medication_diet_instruction')->nullable();
            $table->text('advice_on_discharge')->nullable();
            $table->text('review_after')->nullable();
            $table->string('status',80)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discharge_summaries');
    }
};
