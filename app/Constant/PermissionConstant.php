<?php

namespace App\Constant;

class PermissionConstant
{
    public const USER_INDEX = 'admin.user.index';
    public const USER_CREATE = 'admin.user.create';
    public const USER_EDIT = 'admin.user.edit';
    public const USER_DELETE = 'admin.user.delete';

    public const ROLE_INDEX = 'admin.role.index';
    public const ROLE_CREATE = 'admin.role.create';
    public const ROLE_EDIT = 'admin.role.edit';
    public const ROLE_DELETE = 'admin.role.delete';

    public const PERMISSION_INDEX = 'admin.permission.index';
    public const PERMISSION_CREATE = 'admin.permission.create';
    public const PERMISSION_EDIT = 'admin.permission.edit';
    public const PERMISSION_DELETE = 'admin.permission.delete';

    public const SETTING_INDEX = 'admin.setting.index';
    public const SETTING_UPDATE = 'admin.setting.update';

    public const DASHBOARD_VIEW = 'admin.dashboard.view';

    public static function all(): array
    {
        return [
            self::USER_INDEX,
            self::USER_CREATE,
            self::USER_EDIT,
            self::USER_DELETE,

            self::ROLE_INDEX,
            self::ROLE_CREATE,
            self::ROLE_EDIT,
            self::ROLE_DELETE,

            self::PERMISSION_INDEX,
            self::PERMISSION_CREATE,
            self::PERMISSION_EDIT,
            self::PERMISSION_DELETE,

            self::SETTING_INDEX,
            self::SETTING_UPDATE,

            self::DASHBOARD_VIEW,
        ];
    }
}
