<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')
            ->where('image', 'like', 'https://loremflickr.com/%')
            ->get(['id', 'name'])
            ->each(function ($product): void {
                $image = 'https://placehold.co/800x600/png?text='.rawurlencode($product->name);

                DB::table('products')->where('id', $product->id)->update([
                    'image' => $image,
                    'updated_at' => now(),
                ]);

                DB::table('product_variants')
                    ->where('product_id', $product->id)
                    ->where(function ($query): void {
                        $query->whereNull('image')->orWhere('image', 'like', 'https://loremflickr.com/%');
                    })
                    ->update([
                        'image' => $image,
                        'updated_at' => now(),
                    ]);
            });

        DB::table('product_variants as variants')
            ->join('products', 'products.id', '=', 'variants.product_id')
            ->where('variants.image', 'like', 'https://loremflickr.com/%')
            ->select('variants.id', 'products.image')
            ->get()
            ->each(function ($variant): void {
                DB::table('product_variants')->where('id', $variant->id)->update([
                    'image' => $variant->image,
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        // Không khôi phục URL loremflickr vì host này không ổn định.
    }
};
