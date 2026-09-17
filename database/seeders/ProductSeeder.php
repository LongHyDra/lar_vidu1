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

        DB::table('products')->delete();

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        DB::table('products')->insert([
            // Danh mục 1: Hệ Thống Phanh
            [
                'category_id' => 1,
                'name'        => 'Heo Dầu Brembo 4 Piston Monoblock',
                'price'       => 3500000,
                'stock'       => 15,
                'description' => 'Chính hãng Italy, lực phanh êm ái, độ bền cao. Phù hợp PKL từ 250cc trở lên.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 1,
                'name'        => 'Tay Thắng Brembo Corsa Corta 19RCS',
                'price'       => 6200000,
                'stock'       => 8,
                'description' => 'Tùy chỉnh 3 chế độ thắng N-S-R, phù hợp xe đua và touring.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 1,
                'name'        => 'Đĩa Phanh Wave Wave Alpha 110',
                'price'       => 380000,
                'stock'       => 60,
                'description' => 'Đĩa thép không gỉ, tương thích nhiều loại má phanh phổ thông.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Danh mục 2: Phuộc & Giảm Xóc
            [
                'category_id' => 2,
                'name'        => 'Phuộc Ohlins HO831 cho SH350i',
                'price'       => 14500000,
                'stock'       => 5,
                'description' => 'Hàng xịn Thụy Điển, bình dầu dưới, êm ái, tăng cứng vô cực.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 2,
                'name'        => 'Phuộc RCB C Series Wave/Dream',
                'price'       => 1800000,
                'stock'       => 25,
                'description' => 'Chân phuộc nhôm CNC cứng cáp, màu titan bạc cao cấp.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 2,
                'name'        => 'Giảm Xóc Sau YSS G-Sport Exciter 150',
                'price'       => 2100000,
                'stock'       => 18,
                'description' => 'Hàng Thái Lan chính hãng, tăng cứng 5 nấc, chịu tải tốt.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Danh mục 3: Đèn Chiếu Sáng
            [
                'category_id' => 3,
                'name'        => 'Đèn Trợ Sáng Bi Cầu CX60W',
                'price'       => 1250000,
                'stock'       => 30,
                'description' => 'Công suất 60W, 2 chế độ cos vàng và pha trắng siêu sáng.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 3,
                'name'        => 'Đèn LED Matrix Yamaha R15 V4',
                'price'       => 2800000,
                'stock'       => 12,
                'description' => 'Cụm đèn LED DRL nguyên zin, plug-and-play, ánh sáng 6000K.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Danh mục 4: Pô & Ống Xả
            [
                'category_id' => 4,
                'name'        => 'Pô Akrapovic Carbon Full System CB650R',
                'price'       => 8900000,
                'stock'       => 4,
                'description' => 'Âm thanh uy lực, kèm cổ pô Titan lên màu, giảm 2.5kg.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 4,
                'name'        => 'Pô SC Project CR-T Slip-On Exciter',
                'price'       => 3600000,
                'stock'       => 9,
                'description' => 'Carbon fiber cao cấp, âm thanh trầm ấm, chống rỉ sét tốt.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Danh mục 5: Lốp & Vỏ Xe
            [
                'category_id' => 5,
                'name'        => 'Lốp Michelin City Grip 2 (120/70-12)',
                'price'       => 1150000,
                'stock'       => 40,
                'description' => 'Bám đường cực tốt trong điều kiện mưa và đường dốc trơn trượt.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 5,
                'name'        => 'Lốp Pirelli Diablo Rosso IV (120/70-17)',
                'price'       => 2900000,
                'stock'       => 15,
                'description' => 'Thiết kế cho xe PKL, góc nghiêng tốt, compound mềm thoải mái.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Danh mục 6: Nhông Sên Dĩa
            [
                'category_id' => 6,
                'name'        => 'Bộ Nhông Sên Dĩa DID Vàng 428HD Exciter',
                'price'       => 850000,
                'stock'       => 50,
                'description' => 'Sên phốt cao su êm ái, chịu tải nặng tốt, tuổi thọ dài.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'category_id' => 6,
                'name'        => 'Nhông Sên Dĩa AFAM Racing 520 CBR600RR',
                'price'       => 2400000,
                'stock'       => 7,
                'description' => 'Chuyên dùng xe đua, thép hợp kim crôm-mô, siêu nhẹ.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
