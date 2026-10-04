<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        DB::table('inventory_movements')->delete();
        DB::table('products')->delete();

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $adminId = DB::table('users')->where('role', 'admin')->value('id') ?? 1;

        $items = [
            ['category_id' => 1, 'image' => 'https://loremflickr.com/800/600/brembo,brake,caliper?lock=1', 'name' => 'Heo Dầu Brembo 4 Piston Monoblock', 'price' => 3500000, 'stock' => 15, 'description' => 'Chính hãng Italy, lực phanh độ bền cao. Phù hợp PKL từ 250cc trở lên.'],
            ['category_id' => 1, 'image' => 'https://tinomotor.vn/storage/pagedata/100113/img/images/product/2484_7%20%282%29.JPG', 'name' => 'Tay Thắng Brembo Corsa Corta 19RCS', 'price' => 6200000, 'stock' => 8, 'description' => 'Tùy chỉnh 3 chế độ N-S-R, phù hợp xe đua và touring.'],
            ['category_id' => 1, 'image' => 'https://placehold.co/800x600/111827/ffffff.png?text=Brembo+Corsa+Corta+19RCS', 'name' => 'Đĩa Phanh Wave Wave Alpha 110', 'price' => 380000, 'stock' => 60, 'description' => 'Đĩa thép không gỉ, tương thích nhiều loại má phanh phổ thông.'],
            ['category_id' => 2, 'image' => 'https://alobike.vn/uploads/aphanh-w_1.jpg', 'name' => 'Phuộc Ohlins HO831 cho SH350i', 'price' => 14500000, 'stock' => 5, 'description' => 'Hàng xịn Thụy Điển, bình dầu dưới, tăng cường êm ái khi vào cua.'],
            ['category_id' => 2, 'image' => 'https://placehold.co/800x600/f59e0b/111827.png?text=Ohlins+HO831+SH350i', 'name' => 'Phuộc RCB C Series Wave/Dream', 'price' => 1800000, 'stock' => 25, 'description' => 'Chân phuộc nhôm CNC cứng cáp, màu titan bạc cao cấp.'],
            ['category_id' => 2, 'image' => 'https://product.hstatic.net/200000692635/product/323367931_932328081129998_6421310858167130798_n_1f9b980042314f5ea24401bb71ae5467_master.jpg', 'name' => 'Giảm Xóc Sau YSS G-Sport Exciter 150', 'price' => 2100000, 'stock' => 18, 'description' => 'Hàng Thái Lan chính hãng, tăng chỉnh 5 nấc, chống sàng lắc.'],
            ['category_id' => 3, 'image' => 'https://down-vn.img.susercontent.com/file/2f28c09f6dd98977e3fd8f0fd2cac40b', 'name' => 'Đèn Trợ Sáng Bi Cầu CX60W', 'price' => 1250000, 'stock' => 30, 'description' => 'Công suất 60W, 2 chế độ cos vàng và pha trắng siêu sáng.'],
            ['category_id' => 3, 'image' => 'https://placehold.co/800x600/7c3aed/ffffff.png?text=CX60W+Auxiliary+Light', 'name' => 'Đèn LED Matrix Yamaha R15 V4', 'price' => 2800000, 'stock' => 12, 'description' => 'Cụm đèn LED DRL nguyên zin, plug-and-play, ánh sáng 6000K.'],
            ['category_id' => 4, 'image' => 'https://placehold.co/800x600/0891b2/ffffff.png?text=Yamaha+R15+V4+Matrix+LED', 'name' => 'Pô Akrapovic Carbon Full System CB650R', 'price' => 8900000, 'stock' => 4, 'description' => 'Âm thanh uy lực, cổ pô Titan lên màu, giảm 2.5kg.'],
            ['category_id' => 4, 'image' => 'https://static1.wrs.it/1785417-medium_default/scarico-completo-racing-line-inox-akrapovic-honda-cb-650-r-2026.jpg', 'name' => 'Pô SC Project CR-T Slip-On Exciter', 'price' => 3600000, 'stock' => 9, 'description' => 'Carbon fiber cao cấp, âm thanh trầm ấm, chống rỉ sét.'],
            ['category_id' => 5, 'image' => 'https://placehold.co/800x600/dc2626/ffffff.png?text=SC+Project+CR-T+Exciter', 'name' => 'Lốp Michelin City Grip 2 (120/70-12)', 'price' => 1150000, 'stock' => 40, 'description' => 'Bám đường cực tốt trong điều kiện đường dốc trơn trượt.'],
            ['category_id' => 5, 'image' => 'https://asset.lemansnet.com/media/edge/B/2/B/B2B31F76-1F3D-4538-B791-B696944425EF.png', 'name' => 'Lốp Pirelli Diablo Rosso IV (120/70-17)', 'price' => 2900000, 'stock' => 15, 'description' => 'Thiết kế cho xe PKL, góc nghiêng cua tốt, compound mềm thoải mái.'],
            ['category_id' => 6, 'image' => 'https://placehold.co/800x600/dc2626/ffffff.png?text=Pirelli+Diablo+Rosso+IV', 'name' => 'Bộ Nhông Sên Dĩa DID Vàng 428HD Exciter', 'price' => 850000, 'stock' => 50, 'description' => 'Sên phốt cao su êm ái, chịu lực tốt, tuổi thọ bền bỉ.'],
            ['category_id' => 6, 'image' => 'https://placehold.co/800x600/eab308/111827.png?text=DID+428HD+Exciter', 'name' => 'Nhông Sên Dĩa AFAM Racing 520 CBR600RR', 'price' => 2400000, 'stock' => 7, 'description' => 'Chuyên dùng cho xe đua, thép hợp kim crom, siêu nhẹ.']
        ];

        foreach ($items as $item) {
            $productId = DB::table('products')->insertGetId(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            DB::table('inventory_movements')->insert([
                'product_id' => $productId,
                'user_id' => $adminId,
                'type' => 'in',
                'quantity' => $item['stock'],
                'stock_after' => $item['stock'],
                'note' => 'Khởi tạo tồn kho ban đầu',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
