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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('UPC_code');
            $table->string('product_code');
            $table->foreignId('ProducingCompany_id')->constrained('producing_companies')->cascadeOnDelete();
            $table->string('product_name');
            $table->foreignId('Category_id')->constrained('categories')->cascadeOnDelete();
            $table->text('description');
            $table->string('image');
            $table->double('weight');
            $table->string('WUnit');
            $table->foreign('WUnit')->references('unit_name')->on('units');
            $table->double('size');
            $table->string('SUnit');
            $table->foreign('SUnit')->references('unit_name')->on('units');
            $table->integer('box_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
