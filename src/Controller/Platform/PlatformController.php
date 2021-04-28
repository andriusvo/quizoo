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

namespace App\Controller\Platform;

use App\Constants\AuthorizationRoles;
use App\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PlatformController extends AbstractController
{
    public function redirectAction(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->hasRole(AuthorizationRoles::ROLE_ADMIN) || $user->hasRole(AuthorizationRoles::ROLE_TEACHER)) {
            return $this->redirectToRoute('admin_platform_admin_dashboard_index');
        }

        return $this->redirect('/');
    }
}
