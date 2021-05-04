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

namespace App\Security\User;

use App\Constants\AuthorizationRoles;
use App\Constants\UserEvents;
use App\Entity\User\User;
use App\Provider\UserProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserUpdatePermissionSubscriber implements EventSubscriberInterface
{
    /** @var UserProvider */
    private $userProvider;

    /** @var RequestStack */
    private $requestStack;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        UserProvider $userProvider,
        RequestStack $requestStack,
        LoggerInterface $logger
    ) {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
        $this->userProvider = $userProvider;
    }

    /** {@inheritdoc} */
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvents::UPDATE => 'checkUserPermissionOnUpdate',
        ];
    }

    public function checkUserPermissionOnUpdate(GenericEvent $event): void
    {
        /** @var User $user */
        $user = $event->getSubject();
        $authenticatedUser = $this->userProvider->getUser();

        if ($authenticatedUser->hasRole(AuthorizationRoles::ROLE_ADMIN)) {
            return;
        }

        if ($authenticatedUser !== $user) {
            $this->logger->error(
                '[ADMIN] - Tried to access user that should not to',
                [
                    'user' => $user,
                    'authenticatedUser' => $authenticatedUser->getId(),
                    'requestPath' => $this->requestStack->getCurrentRequest()->getPathInfo(),
                ]
            );

            throw new AccessDeniedException();
        }
    }
}
