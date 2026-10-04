<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $products = [
            ['match' => 'Brembo 4 Piston', 'image' => 'https://loremflickr.com/800/600/brembo,brake,caliper?lock=1', 'variants' => [
                ['sku' => 'BREMBO-4P-BLK', 'name' => 'Đen', 'price' => 3500000, 'stock' => 8, 'weight' => 1800],
                ['sku' => 'BREMBO-4P-RED', 'name' => 'Đỏ', 'price' => 3650000, 'stock' => 7, 'weight' => 1800],
            ]],
            ['match' => 'Brembo Corsa Corta', 'image' => 'https://loremflickr.com/800/600/brembo,motorcycle,lever?lock=2', 'variants' => [
                ['sku' => 'BREMBO-RCS-BLK', 'name' => 'Đen', 'price' => 6200000, 'stock' => 4, 'weight' => 900],
                ['sku' => 'BREMBO-RCS-RED', 'name' => 'Đỏ', 'price' => 6350000, 'stock' => 4, 'weight' => 900],
            ]],
            ['match' => 'Đĩa Phanh Wave', 'image' => 'https://loremflickr.com/800/600/motorcycle,brake,disc?lock=3', 'variants' => [
                ['sku' => 'DISC-WAVE-STD', 'name' => 'Wave 110', 'price' => 380000, 'stock' => 30, 'weight' => 700],
                ['sku' => 'DISC-WAVE-ALPHA', 'name' => 'Wave Alpha 110', 'price' => 395000, 'stock' => 30, 'weight' => 700],
            ]],
            ['match' => 'Ohlins HO831', 'image' => 'https://loremflickr.com/800/600/ohlins,motorcycle,shock?lock=4', 'variants' => [
                ['sku' => 'OHLINS-HO831-BLK', 'name' => 'Đen', 'price' => 14500000, 'stock' => 3, 'weight' => 8500],
                ['sku' => 'OHLINS-HO831-GOLD', 'name' => 'Đen - bình dầu vàng', 'price' => 14900000, 'stock' => 2, 'weight' => 8500],
            ]],
            ['match' => 'RCB C Series', 'image' => 'https://loremflickr.com/800/600/rcb,motorcycle,shock?lock=5', 'variants' => [
                ['sku' => 'RCB-C-WAVE', 'name' => 'Wave', 'price' => 1800000, 'stock' => 13, 'weight' => 4200],
                ['sku' => 'RCB-C-DREAM', 'name' => 'Dream', 'price' => 1800000, 'stock' => 12, 'weight' => 4200],
            ]],
            ['match' => 'YSS G-Sport', 'image' => 'https://loremflickr.com/800/600/yss,motorcycle,shock?lock=6', 'variants' => [
                ['sku' => 'YSS-GS-EX150-BLK', 'name' => 'Đen', 'price' => 2100000, 'stock' => 9, 'weight' => 3800],
                ['sku' => 'YSS-GS-EX150-RED', 'name' => 'Đỏ', 'price' => 2200000, 'stock' => 9, 'weight' => 3800],
            ]],
            ['match' => 'CX60W', 'image' => 'https://loremflickr.com/800/600/motorcycle,auxiliary,light?lock=7', 'variants' => [
                ['sku' => 'CX60W-YELLOW', 'name' => 'Cos vàng', 'price' => 1250000, 'stock' => 15, 'weight' => 1200],
                ['sku' => 'CX60W-WHITE', 'name' => 'Pha trắng', 'price' => 1250000, 'stock' => 15, 'weight' => 1200],
            ]],
            ['match' => 'LED Matrix Yamaha R15', 'image' => 'https://loremflickr.com/800/600/yamaha,r15,headlight?lock=8', 'variants' => [
                ['sku' => 'R15-MATRIX-V3', 'name' => 'R15 V3', 'price' => 2700000, 'stock' => 6, 'weight' => 2400],
                ['sku' => 'R15-MATRIX-V4', 'name' => 'R15 V4', 'price' => 2800000, 'stock' => 6, 'weight' => 2400],
            ]],
            ['match' => 'Akrapovic Carbon', 'image' => 'https://loremflickr.com/800/600/akrapovic,motorcycle,exhaust?lock=9', 'variants' => [
                ['sku' => 'AKRA-CB650R-BLK', 'name' => 'Carbon đen', 'price' => 8900000, 'stock' => 2, 'weight' => 6200],
                ['sku' => 'AKRA-CB650R-TIT', 'name' => 'Carbon - cổ Titan', 'price' => 9200000, 'stock' => 2, 'weight' => 6200],
            ]],
            ['match' => 'SC Project CR-T', 'image' => 'https://loremflickr.com/800/600/sc-project,motorcycle,exhaust?lock=10', 'variants' => [
                ['sku' => 'SCP-CRT-EXC-CARBON', 'name' => 'Carbon', 'price' => 3600000, 'stock' => 5, 'weight' => 3100],
                ['sku' => 'SCP-CRT-EXC-TITAN', 'name' => 'Titan', 'price' => 3800000, 'stock' => 4, 'weight' => 3100],
            ]],
            ['match' => 'Michelin City Grip 2', 'image' => 'https://loremflickr.com/800/600/michelin,motorcycle,tire?lock=11', 'variants' => [
                ['sku' => 'MIC-CITY2-1207012', 'name' => '120/70-12', 'price' => 1150000, 'stock' => 20, 'weight' => 4200],
                ['sku' => 'MIC-CITY2-1107012', 'name' => '110/70-12', 'price' => 1100000, 'stock' => 20, 'weight' => 4000],
            ]],
            ['match' => 'Pirelli Diablo Rosso IV', 'image' => 'https://loremflickr.com/800/600/pirelli,motorcycle,tire?lock=12', 'variants' => [
                ['sku' => 'PDR4-1207017', 'name' => '120/70-17', 'price' => 2900000, 'stock' => 8, 'weight' => 5200],
                ['sku' => 'PDR4-1805517', 'name' => '180/55-17', 'price' => 4200000, 'stock' => 7, 'weight' => 7200],
            ]],
            ['match' => 'DID Vàng 428HD', 'image' => 'https://loremflickr.com/800/600/did,motorcycle,chain?lock=13', 'variants' => [
                ['sku' => 'DID-428HD-EXC-120', 'name' => '120 mắt', 'price' => 850000, 'stock' => 25, 'weight' => 1800],
                ['sku' => 'DID-428HD-EXC-132', 'name' => '132 mắt', 'price' => 900000, 'stock' => 25, 'weight' => 1950],
            ]],
            ['match' => 'AFAM Racing 520', 'image' => 'https://loremflickr.com/800/600/afam,motorcycle,chain?lock=14', 'variants' => [
                ['sku' => 'AFAM-520-CBR-110', 'name' => '110 mắt', 'price' => 2400000, 'stock' => 4, 'weight' => 2100],
                ['sku' => 'AFAM-520-CBR-116', 'name' => '116 mắt', 'price' => 2500000, 'stock' => 3, 'weight' => 2200],
            ]],
        ];

        foreach ($products as $definition) {
            $product = DB::table('products')
                ->where('name', 'like', '%'.$definition['match'].'%')
                ->first(['id', 'image']);

            if (! $product) {
                continue;
            }

            if (empty($product->image)) {
                DB::table('products')->where('id', $product->id)->update([
                    'image' => $definition['image'],
                    'updated_at' => now(),
                ]);
            }

            foreach ($definition['variants'] as $variant) {
                if (DB::table('product_variants')->where('sku', $variant['sku'])->exists()) {
                    continue;
                }

                DB::table('product_variants')->insert([
                    'product_id' => $product->id,
                    'sku' => $variant['sku'],
                    'variant_name' => $variant['name'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                    'weight' => $variant['weight'],
                    'image' => $product->image ?: $definition['image'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('product_variants')->whereIn('sku', [
            'BREMBO-4P-BLK', 'BREMBO-4P-RED', 'BREMBO-RCS-BLK', 'BREMBO-RCS-RED',
            'DISC-WAVE-STD', 'DISC-WAVE-ALPHA', 'OHLINS-HO831-BLK', 'OHLINS-HO831-GOLD',
            'RCB-C-WAVE', 'RCB-C-DREAM', 'YSS-GS-EX150-BLK', 'YSS-GS-EX150-RED',
            'CX60W-YELLOW', 'CX60W-WHITE', 'R15-MATRIX-V3', 'R15-MATRIX-V4',
            'AKRA-CB650R-BLK', 'AKRA-CB650R-TIT', 'SCP-CRT-EXC-CARBON', 'SCP-CRT-EXC-TITAN',
            'MIC-CITY2-1207012', 'MIC-CITY2-1107012', 'PDR4-1207017', 'PDR4-1805517',
            'DID-428HD-EXC-120', 'DID-428HD-EXC-132', 'AFAM-520-CBR-110', 'AFAM-520-CBR-116',
        ])->delete();
    }
};
