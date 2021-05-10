<?php

declare(strict_types=1);

namespace App\Security\User;

use App\Constants\AuthorizationRoles;
use App\Constants\QuizEvents;
use App\Entity\Quiz\Quiz;
use App\Provider\UserProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class QuizUserPermissionSubscriber implements EventSubscriberInterface
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
            QuizEvents::SHOW => 'checkUserPermissionOnQuiz',
            QuizEvents::UPDATE => 'checkUserPermissionOnQuiz',
        ];
    }

    public function checkUserPermissionOnQuiz(GenericEvent $event): void
    {
        /** @var Quiz $quiz */
        $quiz = $event->getSubject();
        $user = $this->userProvider->getUser();

        if ($user->hasRole(AuthorizationRoles::ROLE_ADMIN)) {
            return;
        }

        if ($quiz->getOwner() !== $user) {
            $this->logger->error(
                '[ADMIN] - Tried to access quiz that should not to',
                [
                    'user' => $user,
                    'quiz' => $quiz->getId(),
                    'requestPath' => $this->requestStack->getCurrentRequest()->getPathInfo(),
                ]
            );

            throw new AccessDeniedException();
        }
    }
}
