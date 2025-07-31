<?php

namespace App\Constant;

class PermissionConstant
{
    // Dashboard 
    public const DASHBOARD_INDEX = 'admin.dashboard.index';
    // ==== PURCHASE ==== Quản lý đơn nhập
    public const PURCHASE_INDEX   = 'admin.purchase.index';
    public const PURCHASE_CREATE  = 'admin.purchase.create';
    public const PURCHASE_EDIT    = 'admin.purchase.edit';
    public const PURCHASE_CANCEL  = 'admin.purchase.cancel';
    public const PURCHASE_APPROVED  = 'admin.purchase.approve';
    // ==== INVENTORY AUDIT ==== Quản lý vị trí kho
    public const RECEIVING_INDEX   = 'admin.receiving.index';
    public const RECEIVING_CREATE  = 'admin.receiving.create';
    // ==== SALES ORDER ==== Quản lý đơn xuất
    public const SALES_ORDER_INDEX   = 'admin.sales-order.index';
    public const SALES_ORDER_CREATE  = 'admin.sales-order.create';
    public const SALES_ORDER_APPROVE    = 'admin.sales-order.approve';
    public const SALES_ORDER_REJECT = 'admin.sales-order.reject';
    public const SALES_ORDER_EXPORT  = 'admin.sales-order.export';
    public const SALES_ORDER_COMPLETE  = 'admin.sales-order.complete';




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
    // ==== RANKS ===== Quản lý hạng khách hàng
    public const RANK_INDEX   = 'admin.rank.index';
    public const RANK_CREATE  = 'admin.rank.create';
    public const RANK_EDIT    = 'admin.rank.edit';
    public const RANK_DELETE  = 'admin.rank.delete';

    // ==== INVENTORY ==== Quản lý kho
    public const INVENTORY_INDEX   = 'admin.inventory.index';
    public const INVENTORY_CREATE  = 'admin.inventory.create';
    public const INVENTORY_EDIT    = 'admin.inventory.edit';
    public const INVENTORY_DELETE  = 'admin.inventory.delete';

    // ==== INVENTORY AUDIT ==== Quản lý vị trí kho
    public const INVENTORY_AUDIT_INDEX   = 'admin.inventory-audit.index';
    public const INVENTORY_AUDIT_CREATE  = 'admin.inventory-audit.create';
    public const INVENTORY_AUDIT_EDIT    = 'admin.inventory-audit.edit';
    public const INVENTORY_AUDIT_DELETE  = 'admin.inventory-audit.delete';

    // ==== PRODUCTS ==== Quản lý sản phẩm
    public const PRODUCT_INDEX   = 'admin.product.index';
    public const PRODUCT_CREATE  = 'admin.product.create';
    public const PRODUCT_EDIT    = 'admin.product.edit';
    public const PRODUCT_DELETE  = 'admin.product.delete';





    // ==== CATEGORIES ==== Quản lý danh mục sản phẩm
    public const CATEGORY_INDEX   = 'admin.category.index';
    public const CATEGORY_CREATE  = 'admin.category.create';
    public const CATEGORY_EDIT    = 'admin.category.edit';
    public const CATEGORY_DELETE  = 'admin.category.delete';

    // ==== UNITS ==== Quản lý đơn vị
    public const UNIT_INDEX   = 'admin.unit.index';
    public const UNIT_CREATE  = 'admin.unit.create';
    public const UNIT_EDIT    = 'admin.unit.edit';
    public const UNIT_DELETE  = 'admin.unit.delete';

    // ==== ATTRIBUTES ==== Quản lý thuộc tính
    public const ATTRIBUTE_INDEX   = 'admin.attribute.index';
    public const ATTRIBUTE_CREATE  = 'admin.attribute.create';
    public const ATTRIBUTE_EDIT    = 'admin.attribute.edit';
    public const ATTRIBUTE_DELETE  = 'admin.attribute.delete';

    // ==== REPORT ==== Quản lý báo cáo
    public const REPORT_INDEX   = 'admin.report.index';
    public const REPORT_SUGGEST = 'admin.report.suggest';
    public const REPORT_REVENUE = 'admin.report.revenue';
    public const REPORT_EXPORT = 'admin.report.export';



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
    //  Warehouse Zone

    public const WAREHOUSE_ZONE_INDEX  = 'admin.warehouse-zone.index';
    public const WAREHOUSE_ZONE_CREATE = 'admin.warehouse-zone.create';
    public const WAREHOUSE_ZONE_EDIT   = 'admin.warehouse-zone.edit';
    public const WAREHOUSE_ZONE_DELETE = 'admin.warehouse-zone.delete';
    public static function all(): array
    {
        return [
            [
                'group_permission' => 'DASHBOARD',
                'group_description' => 'Trang chủ',
                'permissions' => [
                    [
                        "description" => "Trang chủ",
                        "name" => self::DASHBOARD_INDEX,
                    ],
                ]
            ],
            // ==== PURCHASE ==== Quản lý đơn nhập
            [
                'group_permission' => 'PERMISSION_PURCHASE',
                'group_description' => 'Quyền quản lý đơn nhập',
                'permissions' => [
                    [
                        "description" => "Danh sách đơn nhập",
                        "name" => self::PURCHASE_INDEX,
                    ],
                    [
                        "description" => "Thêm đơn nhập",
                        "name" => self::PURCHASE_CREATE,
                    ],
                    [
                        "description" => "Cập nhật đơn nhập",
                        "name" => self::PURCHASE_EDIT,
                    ],
                    [
                        "description" => "Từ chối đơn nhập",
                        "name" => self::PURCHASE_CANCEL,
                    ],
                    [
                        "description" => "Duyệt đơn nhập",
                        "name" => self::PURCHASE_APPROVED,
                    ],
                ],
            ],
            // ==== RECEIVING ==== Quản lý nhập kho
            [
                'group_permission' => 'PERMISSION_RECEIVING',
                'group_description' => 'Quyền quản lý nhập kho',
                'permissions' => [
                    [
                        "description" => "Danh sách phiếu nhập kho",
                        "name" => self::RECEIVING_INDEX,
                    ],
                    [
                        "description" => "Thêm phiếu nhập kho",
                        "name" => self::RECEIVING_CREATE,
                    ],
                ],
            ],
            // ==== SALES ORDER ==== Quản lý đơn xuất
            [
                'group_permission' => 'PERMISSION_SALES_ORDER',
                'group_description' => 'Quyền quản lý đơn xuất',
                'permissions' => [
                    [
                        "description" => "Danh sách đơn xuất",
                        "name" => self::SALES_ORDER_INDEX,
                    ],
                    [
                        "description" => "Thêm đơn xuất",
                        "name" => self::SALES_ORDER_CREATE,
                    ],
                    [
                        "description" => "Duyệt đơn xuất",
                        "name" => self::SALES_ORDER_APPROVE,
                    ],
                    [
                        "description" => "Từ chối đơn xuất",
                        "name" => self::SALES_ORDER_REJECT,
                    ],
                    [
                        "description" => "Xuất excel đơn xuất",
                        "name" => self::SALES_ORDER_EXPORT,
                    ],
                    [
                        "description" => "Xác nhận hoàn thành",
                        "name" => self::SALES_ORDER_COMPLETE,
                    ],
                ],
            ],



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
            [
                'group_permission' => 'PERMISSION_RANK',
                'group_description' => 'Quyền quản lý hạng khách hàng',
                'permissions' => [
                    [
                        "description" => "Danh sách hạng khách hàng",
                        "name" => self::RANK_INDEX,
                    ],
                    [
                        "description" => "Thêm hạng khách hàng",
                        "name" => self::RANK_CREATE,
                    ],
                    [
                        "description" => "Cập nhật hạng khách hàng",
                        "name" => self::RANK_EDIT,
                    ],
                    [
                        "description" => "Xóa hạng khách hàng",
                        "name" => self::RANK_DELETE,
                    ],
                ],
            ],

            // ==== INVENTORY ==== Quản lý kho
            [
                'group_permission' => 'PERMISSION_INVENTORY',
                'group_description' => 'Quyền quản lý kho',
                'permissions' => [
                    [
                        "description" => "Danh sách kho",
                        "name" => self::INVENTORY_INDEX,
                    ],
                    [
                        "description" => "Thêm kho",
                        "name" => self::INVENTORY_CREATE,
                    ],
                    [
                        "description" => "Cập nhật kho",
                        "name" => self::INVENTORY_EDIT,
                    ],
                    [
                        "description" => "Xóa kho",
                        "name" => self::INVENTORY_DELETE,
                    ],
                ],
            ],

            // ==== INVENTORY AUDIT ==== Quản lý kiểm kê kho
            [
                'group_permission' => 'PERMISSION_INVENTORY_AUDIT',
                'group_description' => 'Quyền quản lý kiểm kê kho',
                'permissions' => [
                    [
                        "description" => "Danh sách kiểm kê kho",
                        "name" => self::INVENTORY_AUDIT_INDEX,
                    ],
                    [
                        "description" => "Thêm kiểm kê kho",
                        "name" => self::INVENTORY_AUDIT_CREATE,
                    ],
                    [
                        "description" => "Cập nhật kiểm kê kho",
                        "name" => self::INVENTORY_AUDIT_EDIT,
                    ],
                    [
                        "description" => "Xóa kiểm kê kho",
                        "name" => self::INVENTORY_AUDIT_DELETE,
                    ],
                ],
            ],



            // ==== PRODUCTS ==== Quản lý sản phẩm
            [
                'group_permission' => 'PERMISSION_PRODUCT',
                'group_description' => 'Quyền quản lý sản phẩm',
                'permissions' => [
                    [
                        "description" => "Danh sách sản phẩm",
                        "name" => self::PRODUCT_INDEX,
                    ],
                    [
                        "description" => "Thêm sản phẩm",
                        "name" => self::PRODUCT_CREATE,
                    ],
                    [
                        "description" => "Cập nhật sản phẩm",
                        "name" => self::PRODUCT_EDIT,
                    ],
                    [
                        "description" => "Xóa sản phẩm",
                        "name" => self::PRODUCT_DELETE,
                    ],
                ],
            ],





            // ==== CATEGORIES ==== Quản lý danh mục sản phẩm
            [
                'group_permission' => 'PERMISSION_CATEGORY',
                'group_description' => 'Quyền quản lý danh mục sản phẩm',
                'permissions' => [
                    [
                        "description" => "Danh sách danh mục",
                        "name" => self::CATEGORY_INDEX,
                    ],
                    [
                        "description" => "Thêm danh mục",
                        "name" => self::CATEGORY_CREATE,
                    ],
                    [
                        "description" => "Cập nhật danh mục",
                        "name" => self::CATEGORY_EDIT,
                    ],
                    [
                        "description" => "Xóa danh mục",
                        "name" => self::CATEGORY_DELETE,
                    ],
                ],
            ],

            // ==== UNITS ==== Quản lý đơn vị
            [
                'group_permission' => 'PERMISSION_UNIT',
                'group_description' => 'Quyền quản lý đơn vị',
                'permissions' => [
                    [
                        "description" => "Danh sách đơn vị",
                        "name" => self::UNIT_INDEX,
                    ],
                    [
                        "description" => "Thêm đơn vị",
                        "name" => self::UNIT_CREATE,
                    ],
                    [
                        "description" => "Cập nhật đơn vị",
                        "name" => self::UNIT_EDIT,
                    ],
                    [
                        "description" => "Xóa đơn vị",
                        "name" => self::UNIT_DELETE,
                    ],
                ],
            ],

            // ==== ATTRIBUTES ==== Quản lý thuộc tính
            [
                'group_permission' => 'PERMISSION_ATTRIBUTE',
                'group_description' => 'Quyền quản lý thuộc tính',
                'permissions' => [
                    [
                        "description" => "Danh sách thuộc tính",
                        "name" => self::ATTRIBUTE_INDEX,
                    ],
                    [
                        "description" => "Thêm thuộc tính",
                        "name" => self::ATTRIBUTE_CREATE,
                    ],
                    [
                        "description" => "Cập nhật thuộc tính",
                        "name" => self::ATTRIBUTE_EDIT,
                    ],
                    [
                        "description" => "Xóa thuộc tính",
                        "name" => self::ATTRIBUTE_DELETE,
                    ],
                ],
            ],
            // ==== WAREHOUSE ZONES ==== Quản lý khu vực kho
            [
                'group_permission' => 'PERMISSION_WAREHOUSE_ZONE',
                'group_description' => 'Quyền quản lý khu vực kho',
                'permissions' => [
                    [
                        "description" => "Danh sách khu vực kho",
                        "name" => self::WAREHOUSE_ZONE_INDEX,
                    ],
                    [
                        "description" => "Thêm khu vực kho",
                        "name" => self::WAREHOUSE_ZONE_CREATE,
                    ],
                    [
                        "description" => "Cập nhật khu vực kho",
                        "name" => self::WAREHOUSE_ZONE_EDIT,
                    ],
                    [
                        "description" => "Xóa khu vực kho",
                        "name" => self::WAREHOUSE_ZONE_DELETE,
                    ],
                ],
            ],
            [
                'group_permission' => 'PERMISSION_SUPPLIER_TRANSACTION',
                'group_description' => 'Quyền công nợ nhà cung cấp',
                'permissions' => [
                    [
                        "description" => "Danh sách công nợ",
                        "name" => self::SUPPLIER_INDEX,
                    ],
                    [
                        "description" => "Chi tiết công nợ",
                        "name" => self::SUPPLIER_SHOW,
                    ],
                    [
                        "description" => "Cập nhật hạn công nợ",
                        "name" => self::SUPPLIER_UPDATE_CREDIT_DUE_DATE,
                    ],
                    [
                        "description" => "Cập nhật thanh toán công nợ",
                        "name" => self::SUPPLIER_UPDATE_CREDIT_PAID_AMOUNT,
                    ],
                ]
            ],
            [
                'group_permission' => 'PERMISSION_REPORT',
                'group_description' => 'Quyền quản lý báo cáo',
                'permissions' => [
                    [
                        "description" => "Xem danh sách báo cáo",
                        "name" => self::REPORT_INDEX,
                    ],
                    [
                        "description" => "Xem báo cáo gợi ý",
                        "name" => self::REPORT_SUGGEST,
                    ],
                    [
                        "description" => "Xem báo cáo doanh thu",
                        "name" => self::REPORT_REVENUE,
                    ],
                    [
                        "description" => "Xem báo cáo xuất kho",
                        "name" => self::REPORT_EXPORT,
                    ],
                ]
            ],

        ];
    }
}
