<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $variants = [
            ['match' => 'Brembo 4 Piston', 'sku' => 'BREMBO-4P-BLK', 'name' => 'Đen', 'price' => 3500000, 'stock' => 8, 'weight' => 1800],
            ['match' => 'Brembo 4 Piston', 'sku' => 'BREMBO-4P-RED', 'name' => 'Đỏ', 'price' => 3650000, 'stock' => 7, 'weight' => 1800],
            ['match' => 'Brembo Corsa Corta', 'sku' => 'BREMBO-RCS-BLK', 'name' => 'Đen', 'price' => 6200000, 'stock' => 4, 'weight' => 900],
            ['match' => 'Brembo Corsa Corta', 'sku' => 'BREMBO-RCS-RED', 'name' => 'Đỏ', 'price' => 6350000, 'stock' => 4, 'weight' => 900],
            ['match' => 'Đĩa Phanh Wave', 'sku' => 'DISC-WAVE-STD', 'name' => 'Wave 110', 'price' => 380000, 'stock' => 30, 'weight' => 700],
            ['match' => 'Đĩa Phanh Wave', 'sku' => 'DISC-WAVE-ALPHA', 'name' => 'Wave Alpha 110', 'price' => 395000, 'stock' => 30, 'weight' => 700],
            ['match' => 'Ohlins HO831', 'sku' => 'OHLINS-HO831-BLK', 'name' => 'Đen', 'price' => 14500000, 'stock' => 3, 'weight' => 8500],
            ['match' => 'Ohlins HO831', 'sku' => 'OHLINS-HO831-GOLD', 'name' => 'Đen - bình dầu vàng', 'price' => 14900000, 'stock' => 2, 'weight' => 8500],
            ['match' => 'RCB C Series', 'sku' => 'RCB-C-WAVE', 'name' => 'Wave', 'price' => 1800000, 'stock' => 13, 'weight' => 4200],
            ['match' => 'RCB C Series', 'sku' => 'RCB-C-DREAM', 'name' => 'Dream', 'price' => 1800000, 'stock' => 12, 'weight' => 4200],
            ['match' => 'YSS G-Sport', 'sku' => 'YSS-GS-EX150-BLK', 'name' => 'Đen', 'price' => 2100000, 'stock' => 9, 'weight' => 3800],
            ['match' => 'YSS G-Sport', 'sku' => 'YSS-GS-EX150-RED', 'name' => 'Đỏ', 'price' => 2200000, 'stock' => 9, 'weight' => 3800],
            ['match' => 'CX60W', 'sku' => 'CX60W-YELLOW', 'name' => 'Cos vàng', 'price' => 1250000, 'stock' => 15, 'weight' => 1200],
            ['match' => 'CX60W', 'sku' => 'CX60W-WHITE', 'name' => 'Pha trắng', 'price' => 1250000, 'stock' => 15, 'weight' => 1200],
            ['match' => 'LED Matrix Yamaha R15', 'sku' => 'R15-MATRIX-V3', 'name' => 'R15 V3', 'price' => 2700000, 'stock' => 6, 'weight' => 2400],
            ['match' => 'LED Matrix Yamaha R15', 'sku' => 'R15-MATRIX-V4', 'name' => 'R15 V4', 'price' => 2800000, 'stock' => 6, 'weight' => 2400],
            ['match' => 'Akrapovic Carbon', 'sku' => 'AKRA-CB650R-BLK', 'name' => 'Carbon đen', 'price' => 8900000, 'stock' => 2, 'weight' => 6200],
            ['match' => 'Akrapovic Carbon', 'sku' => 'AKRA-CB650R-TIT', 'name' => 'Carbon - cổ Titan', 'price' => 9200000, 'stock' => 2, 'weight' => 6200],
            ['match' => 'SC Project CR-T', 'sku' => 'SCP-CRT-EXC-CARBON', 'name' => 'Carbon', 'price' => 3600000, 'stock' => 5, 'weight' => 3100],
            ['match' => 'SC Project CR-T', 'sku' => 'SCP-CRT-EXC-TITAN', 'name' => 'Titan', 'price' => 3800000, 'stock' => 4, 'weight' => 3100],
            ['match' => 'Michelin City Grip 2', 'sku' => 'MIC-CITY2-1207012', 'name' => '120/70-12', 'price' => 1150000, 'stock' => 20, 'weight' => 4200],
            ['match' => 'Michelin City Grip 2', 'sku' => 'MIC-CITY2-1107012', 'name' => '110/70-12', 'price' => 1100000, 'stock' => 20, 'weight' => 4000],
            ['match' => 'Pirelli Diablo Rosso IV', 'sku' => 'PDR4-1207017', 'name' => '120/70-17', 'price' => 2900000, 'stock' => 8, 'weight' => 5200],
            ['match' => 'Pirelli Diablo Rosso IV', 'sku' => 'PDR4-1805517', 'name' => '180/55-17', 'price' => 4200000, 'stock' => 7, 'weight' => 7200],
            ['match' => 'DID', 'sku' => 'DID-428HD-EXC-120', 'name' => '120 mắt', 'price' => 850000, 'stock' => 25, 'weight' => 1800],
            ['match' => 'DID', 'sku' => 'DID-428HD-EXC-132', 'name' => '132 mắt', 'price' => 900000, 'stock' => 25, 'weight' => 1950],
            ['match' => 'AFAM Racing 520', 'sku' => 'AFAM-520-CBR-110', 'name' => '110 mắt', 'price' => 2400000, 'stock' => 4, 'weight' => 2100],
            ['match' => 'AFAM Racing 520', 'sku' => 'AFAM-520-CBR-116', 'name' => '116 mắt', 'price' => 2500000, 'stock' => 3, 'weight' => 2200],
        ];

        foreach ($variants as $variant) {
            $product = DB::table('products')
                ->where('name', 'like', '%'.$variant['match'].'%')
                ->first(['id', 'image']);

            if (! $product || DB::table('product_variants')->where('sku', $variant['sku'])->exists()) {
                continue;
            }

            DB::table('product_variants')->insert([
                'product_id' => $product->id,
                'sku' => $variant['sku'],
                'variant_name' => $variant['name'],
                'price' => $variant['price'],
                'stock' => $variant['stock'],
                'weight' => $variant['weight'],
                'image' => $product->image,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
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
