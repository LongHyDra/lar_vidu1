<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $images = [
            'Heo Dầu Brembo 4 Piston Monoblock' => 'https://loremflickr.com/800/600/brembo,brake,caliper?lock=1',
            'Tay Thắng Brembo Corsa Corta 19RCS' => 'https://loremflickr.com/800/600/brembo,motorcycle,lever?lock=2',
            'Đĩa Phanh Wave Wave Alpha 110' => 'https://loremflickr.com/800/600/motorcycle,brake,disc?lock=3',
            'Phuộc Ohlins HO831 cho SH350i' => 'https://loremflickr.com/800/600/ohlins,motorcycle,shock?lock=4',
            'Phuộc RCB C Series Wave/Dream' => 'https://loremflickr.com/800/600/rcb,motorcycle,shock?lock=5',
            'Giảm Xóc Sau YSS G-Sport Exciter 150' => 'https://loremflickr.com/800/600/yss,motorcycle,shock?lock=6',
            'Đèn Trợ Sáng Bi Cầu CX60W' => 'https://loremflickr.com/800/600/motorcycle,auxiliary,light?lock=7',
            'Đèn LED Matrix Yamaha R15 V4' => 'https://loremflickr.com/800/600/yamaha,r15,headlight?lock=8',
            'Pô Akrapovic Carbon Full System CB650R' => 'https://loremflickr.com/800/600/akrapovic,motorcycle,exhaust?lock=9',
            'Pô SC Project CR-T Slip-On Exciter' => 'https://loremflickr.com/800/600/sc-project,motorcycle,exhaust?lock=10',
            'Lốp Michelin City Grip 2 (120/70-12)' => 'https://loremflickr.com/800/600/michelin,motorcycle,tire?lock=11',
            'Lốp Pirelli Diablo Rosso IV (120/70-17)' => 'https://loremflickr.com/800/600/pirelli,motorcycle,tire?lock=12',
            'Bộ Nhông Sên Dĩa DID Vàng 428HD Exciter' => 'https://loremflickr.com/800/600/did,motorcycle,chain?lock=13',
            'Nhông Sên Dĩa AFAM Racing 520 CBR600RR' => 'https://loremflickr.com/800/600/afam,motorcycle,chain?lock=14',
        ];

        foreach ($images as $name => $image) {
            DB::table('products')->where('name', $name)->update(['image' => $image]);
        }
    }

    public function down(): void
    {
        DB::table('products')->whereIn('name', [
            'Heo Dầu Brembo 4 Piston Monoblock',
            'Tay Thắng Brembo Corsa Corta 19RCS',
            'Đĩa Phanh Wave Wave Alpha 110',
            'Phuộc Ohlins HO831 cho SH350i',
            'Phuộc RCB C Series Wave/Dream',
            'Giảm Xóc Sau YSS G-Sport Exciter 150',
            'Đèn Trợ Sáng Bi Cầu CX60W',
            'Đèn LED Matrix Yamaha R15 V4',
            'Pô Akrapovic Carbon Full System CB650R',
            'Pô SC Project CR-T Slip-On Exciter',
            'Lốp Michelin City Grip 2 (120/70-12)',
            'Lốp Pirelli Diablo Rosso IV (120/70-17)',
            'Bộ Nhông Sên Dĩa DID Vàng 428HD Exciter',
            'Nhông Sên Dĩa AFAM Racing 520 CBR600RR',
        ])->update(['image' => null]);
    }
};
