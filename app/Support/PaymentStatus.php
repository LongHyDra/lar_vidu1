<?php

namespace App\Support;

class PaymentStatus
{
    public const PENDING = 'pending';
    public const INITIATED = 'initiated';
    public const PAID = 'paid';
    public const FAILED = 'failed';
    public const CANCELLED = 'cancelled';
    public const REFUND_PENDING = 'refund_pending';
    public const REFUNDED = 'refunded';

    public const LABELS = [
        self::PENDING => 'Chờ thanh toán',
        self::INITIATED => 'Đang chờ MoMo',
        self::PAID => 'Đã thanh toán',
        self::FAILED => 'Thanh toán thất bại',
        self::CANCELLED => 'Đã hủy',
        self::REFUND_PENDING => 'Chờ hoàn tiền',
        self::REFUNDED => 'Đã hoàn tiền',
    ];

    public const COD_TRANSITIONS = [
        self::PENDING => [self::PENDING, self::PAID, self::FAILED],
        self::FAILED => [self::FAILED, self::PENDING, self::PAID],
        self::PAID => [self::PAID, self::REFUND_PENDING],
        self::REFUND_PENDING => [self::REFUND_PENDING, self::REFUNDED],
        self::REFUNDED => [self::REFUNDED],
        self::CANCELLED => [self::CANCELLED],
    ];

    public const COLLECTED = [self::PAID, self::REFUND_PENDING, self::REFUNDED];

    public const SELECTION_PRIORITY = "CASE WHEN status IN ('paid', 'refund_pending', 'refunded') THEN 0 ELSE 1 END";

    public static function all(): array
    {
        return array_keys(self::LABELS);
    }

    public static function label(?string $status): string
    {
        return self::LABELS[$status] ?? ($status ?? 'Không xác định');
    }

    // BỔ SUNG: Trả về class màu sắc cho Badge Bootstrap 5 trên giao diện
    public static function badgeClass(?string $status): string
    {
        return match ($status) {
            self::PAID, self::REFUNDED => 'text-bg-success',
            self::FAILED, self::CANCELLED => 'text-bg-danger',
            self::INITIATED => 'text-bg-info text-white',
            self::REFUND_PENDING => 'text-bg-primary',
            default => 'text-bg-warning text-dark',
        };
    }

    public static function isCollected(?string $status): bool
    {
        return in_array($status, self::COLLECTED, true);
    }

    public static function canTransitionTo(?string $from, string $to): bool
    {
        if ($from === $to) {
            return true;
        }

        return in_array($to, self::COD_TRANSITIONS[$from] ?? [], true);
    }
}