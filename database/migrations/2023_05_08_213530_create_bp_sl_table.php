<?php

use App\Models\StoringLocations;
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
        Schema::create('bp_sls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('BranchesProduct_id')->constrained('branches_products')->cascadeOnDelete();
            $table->foreignId('StoringLocation_id')->constrained('storing_locations')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bp_sls');
    }
};
