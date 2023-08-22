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
        Schema::create('producing_companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_code');
            $table->string('company_name');
            $table->foreignId('address_id')->constrained('addresses')->cascadeOnDelete();
            $table->string('phone_number');
            $table->string('email');
            $table->string('company_register')->nullable();
            $table->string('industry_register')->nullable();
            $table->timestamps();
        });
    }
//
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producing_companies');
    }
};
