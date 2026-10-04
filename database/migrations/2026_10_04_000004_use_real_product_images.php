<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $images = [
            'Brembo 4 Piston' => 'https://www.hagl-parts.com/media/catalog/product/cache/207e23213cf636ccdef205098cf3c8a3/2/2/220c78310.jpg',
            'Brembo Corsa Corta' => 'https://tgp-shop.com/cdn/shop/files/5bce005ed2285b9662b8631f770f6c29.jpg?v=1740466281&width=1946',
            'Đĩa Phanh Wave' => 'https://my-live-01.slatic.net/p/cf6e1dd28219e769055e073373d3f9f0.png',
            'Ohlins HO831' => 'https://shop2banh.vn/images/thumbs/2021/11/phuoc-ohlins-ho-817-chinh-hang-cho-honda-sh300i-sh350i-1601-slide-products-61a0baf12a55d.jpg',
            'RCB C Series' => 'https://product.hstatic.net/200000692635/product/323367931_932328081129998_6421310858167130798_n_1f9b980042314f5ea24401bb71ae5467_master.jpg',
            'YSS G-Sport' => 'https://shop2banh.vn/images/thumbs/2021/03/phuoc-yss-g-sport-chinh-hang-cho-exciter-150-1133-slide-products-60595bf403a4d.jpg',
            'CX60W' => 'https://www.kemimoto.com/cdn/shop/files/71sJn1E9s_L._AC_SL1500_1800x1800.jpg?v=1761711431',
            'LED Matrix Yamaha R15' => 'https://i.ebayimg.com/images/g/CuEAAeSwvN5oGewP/s-l1200.jpg',
            'Akrapovic Carbon' => 'https://bevomotor.vn/thumbs/640x560x1/upload/product/4262a1ba-a82f-4f48-922b-9aafe0795bfc-7319.png',
            'SC Project CR-T' => 'https://www.imotorcycle.jp/cdn/shop/products/cr-t_carbonio_front_1200x1200.jpg?v=1633449594',
            'Michelin City Grip 2' => 'https://dxm.contentcenter.michelin.com/api/wedia/dam/transform/b98rpyxf61b4x5tgmdzz66qkpc/mo-105_3528704285969_tire_michelin_city_grip-2_120-slash-70-12-51s_a_main_2-55_nopad.webp?height=500&t=resize',
            'Pirelli Diablo Rosso IV' => 'https://admin.massdepot.com/images/D/Motorcycle-Tires-3978600-4074700-P01-100-detailed-image-2.jpg',
            'DID Vàng 428HD' => 'https://www.motorcycleproducts.co.uk/images/did_428_hd_gold.jpg',
            'AFAM Racing 520' => 'https://www.francetrialclassic.com/4283-large_default/520-afam-chain-reinforced-120-links.jpg',
        ];

        foreach ($images as $productMatch => $image) {
            $products = DB::table('products')
                ->where('name', 'like', '%'.$productMatch.'%')
                ->where(function ($query): void {
                    $query->whereNull('image')
                        ->orWhere('image', 'like', 'https://loremflickr.com/%')
                        ->orWhere('image', 'like', 'https://placehold.co/%');
                })
                ->pluck('id');

            if ($products->isEmpty()) {
                continue;
            }

            DB::table('products')->whereIn('id', $products)->update([
                'image' => $image,
                'updated_at' => now(),
            ]);

            DB::table('product_variants')
                ->whereIn('product_id', $products)
                ->where(function ($query): void {
                    $query->whereNull('image')
                        ->orWhere('image', 'like', 'https://loremflickr.com/%')
                        ->orWhere('image', 'like', 'https://placehold.co/%');
                })
                ->update([
                    'image' => $image,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // Không khôi phục các URL ảnh cũ vì chúng không ổn định.
    }
};
