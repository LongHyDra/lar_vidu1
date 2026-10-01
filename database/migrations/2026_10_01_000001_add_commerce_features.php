<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('user_addresses')) {
            Schema::create('user_addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('label')->default('Nhà riêng');
                $table->string('recipient_name');
                $table->string('phone', 20);
                $table->string('address');
                $table->unsignedInteger('district_id')->nullable();
                $table->string('ward_code')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
                $table->index(['user_id', 'is_default']);
            });
        }

        if (! Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
                $table->json('items')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->enum('type', ['percent', 'fixed'])->default('percent');
                $table->decimal('value', 12, 2);
                $table->decimal('minimum_order', 12, 2)->default(0);
                $table->unsignedInteger('usage_limit')->nullable();
                $table->unsignedInteger('used_count')->default(0);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('coupon_redemptions')) {
            Schema::create('coupon_redemptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
                $table->decimal('discount_amount', 12, 2);
                $table->timestamps();
                $table->unique(['coupon_id', 'user_id']);
            });
        }

        if (! Schema::hasTable('product_reviews')) {
            Schema::create('product_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
                $table->unsignedTinyInteger('rating');
                $table->text('body')->nullable();
                $table->string('status')->default('pending');
                $table->timestamps();
                $table->unique(['product_id', 'user_id', 'order_id']);
            });
        }

        if (! Schema::hasTable('site_notifications')) {
            Schema::create('site_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->text('body');
                $table->string('url')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'read_at']);
            });
        }

        if (! Schema::hasTable('admin_audits')) {
            Schema::create('admin_audits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('action');
                $table->string('auditable_type')->nullable();
                $table->unsignedBigInteger('auditable_id')->nullable();
                $table->json('changes')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->timestamps();
                $table->index(['auditable_type', 'auditable_id']);
            });
        }

        if (! Schema::hasColumn('orders', 'discount_amount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('total_price');
                $table->string('coupon_code')->nullable()->after('discount_amount');
            });
        }

        if (! Schema::hasColumn('inventory_movements', 'variant_id')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'discount_amount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn(['discount_amount', 'coupon_code']);
            });
        }
        if (Schema::hasColumn('inventory_movements', 'variant_id')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->dropForeign(['variant_id']);
                $table->dropColumn('variant_id');
            });
        }
        Schema::dropIfExists('admin_audits');
        Schema::dropIfExists('site_notifications');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('coupon_redemptions');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('user_addresses');
    }
};
