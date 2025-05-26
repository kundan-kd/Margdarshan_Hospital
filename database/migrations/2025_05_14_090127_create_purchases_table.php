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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('type',20)->nullable();
            $table->string('bill_no',100)->nullable();
            $table->string('vendor_id',100)->nullable();
            $table->double('total_amount')->nullable();
            $table->double('total_discount_per')->nullable();
            $table->double('total_discount')->nullable();
            $table->double('total_tax')->nullable();
            $table->double('net_amount')->nullable();
            $table->string('payment_mode',40)->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('due')->nullable();
            $table->text('naration')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
