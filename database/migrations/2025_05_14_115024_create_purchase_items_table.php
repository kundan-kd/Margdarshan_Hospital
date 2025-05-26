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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id')->nullable();
            $table->string('category_id',80)->nullable();
            $table->string('name_id',100)->nullable();
            $table->string('batch_no',100)->nullable();
            $table->string('expiry',40)->nullable();
            $table->double('mrp')->nullable();
            $table->double('sales_price')->nullable();
            $table->double('tax')->nullable();
            $table->double('qty')->nullable();
            $table->double('purchase_rate')->nullable();
            $table->double('amount')->nullable();
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
        Schema::dropIfExists('purchase_items');
    }
};
