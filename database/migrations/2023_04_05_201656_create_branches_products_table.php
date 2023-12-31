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
        Schema::create('branches_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('Supplier_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->integer('in_quantity');
            $table->integer('recent_quantity');
            $table->date('date_in');
            $table->date('prod_date');
            $table->date('exp_date');
            $table->string('purchase_num');
            $table->double('buying_cost');
            $table->double('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches_products');
    }
};
