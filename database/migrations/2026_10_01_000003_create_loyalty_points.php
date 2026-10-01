<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'loyalty_points')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedInteger('loyalty_points')->default(0)->after('role');
            });
        }

        if (! Schema::hasTable('loyalty_transactions')) {
            Schema::create('loyalty_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
                $table->integer('points');
                $table->string('type');
                $table->string('description');
                $table->timestamps();
                $table->unique(['user_id', 'order_id', 'type']);
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
        if (Schema::hasColumn('users', 'loyalty_points')) {
            Schema::table('users', fn (Blueprint $table) => $table->dropColumn('loyalty_points'));
        }
    }
};
