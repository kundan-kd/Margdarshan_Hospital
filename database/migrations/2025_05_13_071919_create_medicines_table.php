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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('unit')->nullable();
            $table->string('re_ordering_level',100)->nullable();
            $table->string('rack',80)->nullable();
            $table->string('composition',200)->nullable();
            $table->double('taxes',50)->nullable();
            $table->string('box_packing',100)->nullable();
            $table->string('naration',200)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
