<?php

namespace App\Constant;

class RoleConstant{
    public const admin = [
        'text' => "Quản trị hệ thống",
        'value' => "admin",
    ];
    public const manager = [
        'text' => "Quản lý",
        'value' => "manager",
    ];
    public const staff = [
        'text' => "Nhân viên kho",
        'value' => "staff",
    ];
    public const shipper = [
        'text' => "Người giao hàng",
        'value' => "shipper",
    ];

}

