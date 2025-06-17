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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Store product name at time of order
            $table->string('product_sku'); // Store SKU at time of order
            $table->decimal('product_price', 10, 2); // Store price at time of order
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2); // quantity * product_price
            $table->json('product_attributes')->nullable(); // Size, color, etc at time of order
            $table->timestamps();
            
            $table->index(['order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
