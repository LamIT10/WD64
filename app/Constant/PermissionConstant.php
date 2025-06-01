<?php

namespace App\Constant;

class PermissionConstant
{
    // ==== USERS ====
    public const USER_INDEX = 'admin.user.index';
    public const USER_CREATE = 'admin.user.create';
    public const USER_EDIT = 'admin.user.edit';
    public const USER_DELETE = 'admin.user.delete';
    public const USER_SHOW = 'admin.user.show';
    public const USER_UPDATE = 'admin.user.update';
    public const USER_STORE = 'admin.user.store';

    // ==== ROLES ====
    public const ROLE_INDEX = 'admin.role.index';
    public const ROLE_CREATE = 'admin.role.create';
    public const ROLE_EDIT = 'admin.role.edit';
    public const ROLE_DELETE = 'admin.role.delete';
    public const ROLE_SHOW = 'admin.role.show';
    public const ROLE_UPDATE = 'admin.role.update';
    public const ROLE_STORE = 'admin.role.store';

    // ==== PERMISSIONS ====
    public const PERMISSION_INDEX = 'admin.permission.index';
    public const PERMISSION_CREATE = 'admin.permission.create';
    public const PERMISSION_EDIT = 'admin.permission.edit';
    public const PERMISSION_DELETE = 'admin.permission.delete';
    public const PERMISSION_SHOW = 'admin.permission.show';
    public const PERMISSION_UPDATE = 'admin.permission.update';
    public const PERMISSION_STORE = 'admin.permission.store';

    // ==== CUSTOMERS ====
    public const CUSTOMER_INDEX = 'admin.customers.index';
    public const CUSTOMER_CREATE = 'admin.customers.create';
    public const CUSTOMER_EDIT = 'admin.customers.edit';
    public const CUSTOMER_DELETE = 'admin.customers.destroy';
    public const CUSTOMER_SHOW = 'admin.customers.show';
    public const CUSTOMER_UPDATE = 'admin.customers.update';
    public const CUSTOMER_STORE = 'admin.customers.store';
    public const CUSTOMER_RANK_STORE = 'admin.customers.ranks.store';

    // ==== SUPPLIERS ====
    public const SUPPLIER_INDEX = 'admin.suppliers.index';
    public const SUPPLIER_CREATE = 'admin.suppliers.create';
    public const SUPPLIER_STORE = 'admin.suppliers.store';

    // ==== SETTINGS ====
    public const SETTING_INDEX = 'admin.setting.index';
    public const SETTING_UPDATE = 'admin.setting.update';

    public const CATEGORY_INDEX = 'admin.category.index';

    public static function all(): array
    {
        return [
            // Users
            self::USER_INDEX,
            self::USER_CREATE,
            self::USER_EDIT,
            self::USER_DELETE,
            self::USER_SHOW,
            self::USER_UPDATE,
            self::USER_STORE,

            // Roles
            self::ROLE_INDEX,
            self::ROLE_CREATE,
            self::ROLE_EDIT,
            self::ROLE_DELETE,
            self::ROLE_SHOW,
            self::ROLE_UPDATE,
            self::ROLE_STORE,

            // Permissions
            self::PERMISSION_INDEX,
            self::PERMISSION_CREATE,
            self::PERMISSION_EDIT,
            self::PERMISSION_DELETE,
            self::PERMISSION_SHOW,
            self::PERMISSION_UPDATE,
            self::PERMISSION_STORE,

            // Customers
            self::CUSTOMER_INDEX,
            self::CUSTOMER_CREATE,
            self::CUSTOMER_EDIT,
            self::CUSTOMER_DELETE,
            self::CUSTOMER_SHOW,
            self::CUSTOMER_UPDATE,
            self::CUSTOMER_STORE,
            self::CUSTOMER_RANK_STORE,

            // Suppliers
            self::SUPPLIER_INDEX,
            self::SUPPLIER_CREATE,
            self::SUPPLIER_STORE,

            // Settings
            self::SETTING_INDEX,
            self::SETTING_UPDATE,

            self::CATEGORY_INDEX,

     
        ];
    }
}
