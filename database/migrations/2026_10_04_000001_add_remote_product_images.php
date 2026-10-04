<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $images = [
            'Heo Dầu Brembo 4 Piston Monoblock' => 'https://tinomotor.vn/storage/pagedata/100113/img/images/product/2484_7%20%282%29.JPG',
            'Tay Thắng Brembo Corsa Corta 19RCS' => 'https://placehold.co/800x600/111827/ffffff.png?text=Brembo+Corsa+Corta+19RCS',
            'Đĩa Phanh Wave Wave Alpha 110' => 'https://alobike.vn/uploads/aphanh-w_1.jpg',
            'Phuộc Ohlins HO831 cho SH350i' => 'https://placehold.co/800x600/f59e0b/111827.png?text=Ohlins+HO831+SH350i',
            'Phuộc RCB C Series Wave/Dream' => 'https://product.hstatic.net/200000692635/product/323367931_932328081129998_6421310858167130798_n_1f9b980042314f5ea24401bb71ae5467_master.jpg',
            'Giảm Xóc Sau YSS G-Sport Exciter 150' => 'https://down-vn.img.susercontent.com/file/2f28c09f6dd98977e3fd8f0fd2cac40b',
            'Đèn Trợ Sáng Bi Cầu CX60W' => 'https://placehold.co/800x600/7c3aed/ffffff.png?text=CX60W+Auxiliary+Light',
            'Đèn LED Matrix Yamaha R15 V4' => 'https://placehold.co/800x600/0891b2/ffffff.png?text=Yamaha+R15+V4+Matrix+LED',
            'Pô Akrapovic Carbon Full System CB650R' => 'https://static1.wrs.it/1785417-medium_default/scarico-completo-racing-line-inox-akrapovic-honda-cb-650-r-2026.jpg',
            'Pô SC Project CR-T Slip-On Exciter' => 'https://placehold.co/800x600/dc2626/ffffff.png?text=SC+Project+CR-T+Exciter',
            'Lốp Michelin City Grip 2 (120/70-12)' => 'https://asset.lemansnet.com/media/edge/B/2/B/B2B31F76-1F3D-4538-B791-B696944425EF.png',
            'Lốp Pirelli Diablo Rosso IV (120/70-17)' => 'https://placehold.co/800x600/dc2626/ffffff.png?text=Pirelli+Diablo+Rosso+IV',
            'Bộ Nhông Sên Dĩa DID Vàng 428HD Exciter' => 'https://placehold.co/800x600/eab308/111827.png?text=DID+428HD+Exciter',
            'Nhông Sên Dĩa AFAM Racing 520 CBR600RR' => 'https://placehold.co/800x600/7c3aed/ffffff.png?text=AFAM+520+CBR600RR',
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
