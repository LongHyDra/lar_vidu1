<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        DB::table('categories')->delete();

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        DB::table('categories')->insert([
            ['name' => 'Hệ Thống Phanh', 'description' => 'Heo dầu, tay thắng, đĩa phanh, bố thắng các loại xe máy.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Phuộc & Giảm Xóc', 'description' => 'Phuộc nhún, giảm xóc sau, lò xo tăng cứng cho xe máy phân khối lớn và phổ thông.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Đèn Chiếu Sáng', 'description' => 'Đèn pha LED, đèn bi cầu, đèn trợ sáng, đèn xi nhan độ.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pô & Ống Xả', 'description' => 'Pô thể thao, cổ pô Titan, pô carbon cho các dòng xe phân khối lớn.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lốp & Vỏ Xe', 'description' => 'Lốp xe máy các thương hiệu Michelin, Pirelli, Bridgestone, IRC.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nhông Sên Dĩa', 'description' => 'Bộ nhông sên dĩa DID, RK, AFAM cho xe số và xe côn tay.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}