<?php

declare(strict_types=1);

namespace App\Constants;

class AuthorizationRoles
{
    public const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_TEACHER = 'ROLE_TEACHER';
    public const ROLE_STUDENT = 'ROLE_STUDENT';

    public const ADMIN_ROLES = [
        self::ROLE_SUPERADMIN,
        self::ROLE_ADMIN,
    ];
}
