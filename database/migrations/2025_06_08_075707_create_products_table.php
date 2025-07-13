<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();

        // ✅ THIS IS THE NEW, IMPORTANT LINE
        // This links the product to a contract.
        $table->foreignId('contract_id')->constrained()->onDelete('cascade');

        // Your existing product columns
        $table->string('item')->nullable();
        $table->text('item_description')->nullable();
        $table->text('specifications')->nullable();
        $table->string('unit');
        $table->integer('quantity');
        $table->decimal('unit_price', 8, 2);
        $table->decimal('discount', 8, 2)->default(0);
        $table->decimal('price_after_discount', 8, 2);
        $table->decimal('price_with_vat', 8, 2);
        $table->decimal('total_price', 10, 2);
        $table->timestamps();
    });
}
};