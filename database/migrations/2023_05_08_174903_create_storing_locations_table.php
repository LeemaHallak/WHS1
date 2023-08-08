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
        Schema::create('storing_locations', function (Blueprint $table) {
            $table->id();
            $table->string('main_section');
            $table->string('section');
            $table->foreignId('branch_id')->constrained('branches');
            $table->integer('available_quantity');
            $table->integer('unavailable_quantity');
            $table->string('locationNum')->nullable();
            $table->timestamps();
            $table->unique(['main_section','section','branch_id']);
        });



        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storing_locations');
    }
};
