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
            $table->string('name');                        // e.g. Mabati, Hotpot, Tank
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);  // optional reference price
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Add product_id and product_description to loans table
        Schema::table('loans', function (Blueprint $table) {
            $table->foreignId('product_id')
                  ->nullable()
                  ->constrained('products')
                  ->nullOnDelete();
            $table->text('product_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'product_description']);
        });

        Schema::dropIfExists('products');
    }
};