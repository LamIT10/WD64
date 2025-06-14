<?php

namespace App\Constant;

class TransactionStatusConstant{
    public const pending = [
        'text' => "Chưa xác nhận",
        'value' => "pennding",
    ];
    public const confirmed = [
        'text' => "Đã xác nhận",
        'value' => "confirmed",
    ];
    public const processing = [
        'text' => "Đang đóng gói",
        'value' => "processing",
    ];
    public const shipped = [
        'text' => "Đã giao cho đơn vị vận chuyển",
        'value' => "shipped",
    ];
    public const delivered = [
        'text' => "Giao thành công",
        'value' => "delivered",
    ];
    public const completed = [
        'text' => "Đơn hàng hoàn thành",
        'value' => "completed",
    ];
}

