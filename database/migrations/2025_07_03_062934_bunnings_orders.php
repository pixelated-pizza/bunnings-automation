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
        Schema::create('bunnings_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('status')->nullable();
            $table->string('sales_channel')->nullable();
            $table->dateTime('date_placed')->nullable();
            $table->json('ship_address')->nullable();
            $table->json('order_lines')->nullable();
            $table->string('tracking_number')->nullable(); 
            $table->string('carrier')->nullable();         
            $table->dateTime('dispatched_at')->nullable(); 
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bunnings_orders');
    }
};
