<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber\Quiz;

use App\Entity\Quiz\Quiz;
use App\Mailer\NewQuizMailer;
use App\Manager\ResponseManager;
use App\Provider\UserProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class QuizTypeSubscriber implements EventSubscriberInterface
{
    private const CODE_PREFIX = 'VU-';

    /** @var UserProvider */
    private $userProvider;

    /** @var ResponseManager */
    private $responseManager;

    /** @var NewQuizMailer */
    private $newQuizMailer;

    public function __construct(
        UserProvider $userProvider,
        ResponseManager $responseManager,
        NewQuizMailer $newQuizMailer
    ) {
        $this->userProvider = $userProvider;
        $this->responseManager = $responseManager;
        $this->newQuizMailer = $newQuizMailer;
    }

    /** {@inheritdoc} */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
            FormEvents::POST_SUBMIT => 'postSubmit',
        ];
    }

    public function onSubmit(FormEvent $event): void
    {
        /** @var Quiz $quiz */
        $quiz = $event->getData();
        $quiz->setOwner($this->userProvider->getUser());

        $code = self::CODE_PREFIX . time();
        $quiz->setCode($code);
    }

    public function postSubmit(FormEvent $event): void
    {
        /** @var Quiz $quiz */
        $quiz = $event->getData();
        $form = $event->getForm();

        if (false === $form->isSubmitted() || false === $form->isValid()) {
            return;
        }

        if (null === $quiz->getId()) {
            $this->responseManager->createResponsesForGroups($quiz);
            $this->newQuizMailer->sendQuizNotificationMail($quiz);
        }
    }
}
