<?php

/*
 * @copyright C MB Storiukas
 *
 * This Software is the property of Storiukas
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact MB Storiukas
 * E-mail: info@storiukas.lt
 * https://www.storio.lt
 */

declare(strict_types=1);

namespace App\Security\User;

use App\Constants\AuthorizationRoles;
use App\Constants\QuizEvents;
use App\Constants\SubjectEvents;
use App\Entity\Quiz\Quiz;
use App\Entity\Subject\Subject;
use App\Provider\UserProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SubjectUserPermissionSubscriber implements EventSubscriberInterface
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
            SubjectEvents::SHOW => 'checkUserPermissionOnSubject',
            SubjectEvents::UPDATE => 'checkUserPermissionOnSubject',
        ];
    }

    public function checkUserPermissionOnSubject(GenericEvent $event): void
    {
        /** @var Subject $subject */
        $subject = $event->getSubject();
        $user = $this->userProvider->getUser();

        if ($user->hasRole(AuthorizationRoles::ROLE_ADMIN)) {
            return;
        }

        if ($subject->getSupervisor() !== $user) {
            $this->logger->error(
                '[ADMIN] - Tried to access subject that should not to',
                [
                    'user' => $user,
                    'subject' => $subject->getId(),
                    'requestPath' => $this->requestStack->getCurrentRequest()->getPathInfo(),
                ]
            );

            throw new AccessDeniedException();
        }
    }
}
