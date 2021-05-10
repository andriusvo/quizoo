<?php

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

        return $this->redirectToRoute('app_front_dashboard');
    }
}
