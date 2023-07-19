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
        Schema::create('inner_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('BranchProduct_id')->constrained('branches_products');
            $table->foreignId('SourceBranch_id')->constrained('branches');
            $table->foreignId('DestinationBranch_id')->constrained('branches');
            $table->integer('quantity');
            $table->date('transaction_date');
            $table->double('transaction_cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inner_transactions');
    }
};
