<?php

namespace App\Constant;

class PermissionConstant
{
    // Dashboard 
    public const DASHBOARD_INDEX = 'admin.dashboard.index';
    // ==== CUSTOMERS ==== Quản lý khách hàng
    public const CUSTOMER_INDEX = 'admin.customers.index';
    public const CUSTOMER_CREATE = 'admin.customers.create';
    public const CUSTOMER_EDIT = 'admin.customers.edit';
    public const CUSTOMER_DELETE = 'admin.customers.destroy';
    public const CUSTOMER_SHOW = 'admin.customers.show';
    public const CUSTOMER_RANK_STORE = 'admin.customers.ranks.store';
    // ====USER===== Quản lý nhân viên
    public const USER_INDEX = 'admin.user.index';
    public const USER_CREATE = 'admin.user.create';
    public const USER_EDIT = 'admin.user.edit';
    public const USER_DELETE = 'admin.user.delete';
    public const USER_SHOW = 'admin.user.show';
    // ==== RANKS===== Quản lý hạng khách hàng
    // ==== INVENTORY ==== Quản lý kho
    // ==== INVENTORY LOCATION ==== Quản lý vị trí kho
    // ==== PRODUCTS ==== Quản lý sản phẩm
    // ==== PURCHASE ==== Quản lý đơn nhập 
    // ==== SALES ORDER ==== Quản lý đơn xuất
    // ==== CATEGORIES ==== Quản lý danh mục sản phẩm
    // ==== UNITS ==== Quản lý đơn vị 
    // ==== ATTRIBUTES ==== Quản lý đơn vị






    // ==== ROLES ====
    public const ROLE_INDEX = 'admin.role.index';
    public const ROLE_EDIT = 'admin.role.edit';



    //  Supplier transaction
    public const SUPPLIER_TRANSACTION_INDEX = 'admin.supplier_transaction.index';
    public const SUPPLIER_TRANSACTION_SHOW = 'admin.supplier_transaction.show';
    public const SUPPLIER_TRANSACTION_UPDATE_CREDIT_DUE_DATE = 'admin.supplier_transaction.update_credit_due_date';
    public const SUPPLIER_TRANSACTION_UPDATE_CREDIT_PAID_AMOUNT = 'admin.supplier_transaction.update_paid_amount';

    //  Supplier transaction
    public const SUPPLIER_INDEX = 'admin.supplier.index';
    public const SUPPLIER_SHOW = 'admin.supplier.show';
    public const SUPPLIER_UPDATE_CREDIT_DUE_DATE = 'admin.supplier.update_credit_due_date';
    public const SUPPLIER_UPDATE_CREDIT_PAID_AMOUNT = 'admin.supplier.update_paid_amount';
    public static function all(): array
    {
        return [
            [
                'group_permission' => 'PERMISSION_SUPPLIER_TRANSACTION',
                'group_description' => 'Quyền công nợ nhà cung cấp',
                'permissions' => [
                    [
                        "description" => "Danh sách công nợ",
                        "name" => self::SUPPLIER_TRANSACTION_INDEX,
                    ],
                    [
                        "description" => "Chi tiết công nợ",
                        "name" => self::SUPPLIER_TRANSACTION_SHOW,
                    ],
                    [
                        "description" => "Cập nhật hạn công nợ",
                        "name" => self::SUPPLIER_TRANSACTION_UPDATE_CREDIT_DUE_DATE,
                    ],
                    [
                        "description" => "Cập nhật thành toán công nợ",
                        "name" => self::SUPPLIER_TRANSACTION_UPDATE_CREDIT_PAID_AMOUNT,
                    ],
                ]
            ],
            // Users
            [
                "group_permission" => "PERMISSION_USER",
                "group_description" => "Quyền người dùng",
                "permissions" => [
                    [
                        "description" => "Danh sách người dùng",
                        "name" => self::USER_INDEX,
                    ],
                    [
                        "description" => "Tạo người dùng",
                        "name" => self::USER_CREATE,
                    ],
                    [
                        "description" => "Sửa người dùng",
                        "name" => self::USER_EDIT,
                    ],
                    [
                        "description" => "Xoá người dùng",
                        "name" => self::USER_DELETE,
                    ],
                    [
                        "description" => "Xem người dùng",
                        "name" => self::USER_SHOW,
                    ],
                ]
            ],

            [
                "group_permission" => "PERMISSION_ROLE",
                "group_description" => "Quyền vai trò",
                "permissions" => [
                    [
                        "description" => "Danh sách vai trò",
                        "name" => self::ROLE_INDEX,
                    ],

                    [
                        "description" => "Sửa vai trò",
                        "name" => self::ROLE_EDIT,
                    ],
                ]
            ],


            [
                "group_permission" => "PERMISSION_CUSTOMER",
                "group_description" => "Quyền khách hàng",
                "permissions" => [
                    [
                        "description" => "Danh sách khách hàng",
                        "name" => self::CUSTOMER_INDEX,
                    ],
                    [
                        "description" => "Tạo khách hàng",
                        "name" => self::CUSTOMER_CREATE,
                    ],
                    [
                        "description" => "Sửa khách hàng",
                        "name" => self::CUSTOMER_EDIT,
                    ],
                    [
                        "description" => "Xoá khách hàng",
                        "name" => self::CUSTOMER_DELETE,
                    ],
                    [
                        "description" => "Xem khách hàng",
                        "name" => self::CUSTOMER_SHOW,
                    ],
                ]
            ],

        ];
    }
}
