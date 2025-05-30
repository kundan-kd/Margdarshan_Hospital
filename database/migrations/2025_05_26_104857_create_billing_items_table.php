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
        Schema::create('billing_items', function (Blueprint $table) {
            $table->id();
            $table->integer('billing_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('name_id')->nullable();
            $table->string('batch_no',100)->nullable();
            $table->string('expiry',40)->nullable();
            $table->integer('qty')->nullable();
            $table->integer('avl_qty')->nullable();
            $table->double('sales_price')->nullable();
            $table->double('tax_per')->nullable();
            $table->double('tax_amount')->nullable();
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
        Schema::dropIfExists('billing_items');
    }
};
