<?php

/*
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
 */

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
