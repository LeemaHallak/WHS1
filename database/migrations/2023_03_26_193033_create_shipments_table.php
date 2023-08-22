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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipping_company');
            $table->enum('I\O', ['in', 'out']);
            $table->foreignId('SourceAddress_id')->constrained('addresses')->cascadeOnDelete();
            $table->foreignId('DestinationAddress_id')->constrained('addresses')->cascadeOnDelete();
            $table->date('shipment_date');
            $table->enum('shipment_type', ['overland', 'air', 'sea']);
            $table->integer('max_quantity');
            $table->integer('shipment_quantity');
            $table->double('shipProducts_cost');
            $table->double('shipment_cost');
            $table->boolean('arrived');
            $table->unique(array('shipment_date','shipment_type'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
