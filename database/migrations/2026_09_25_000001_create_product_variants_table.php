<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('variant_name');
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->integer('weight')->default(250);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        if (Schema::hasTable('order_items') && !Schema::hasColumn('order_items', 'variant_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'variant_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['variant_id']);
                $table->dropColumn('variant_id');
            });
        }
        Schema::dropIfExists('product_variants');
    }
};