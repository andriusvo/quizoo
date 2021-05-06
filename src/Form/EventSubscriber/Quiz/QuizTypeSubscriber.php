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

namespace App\Form\EventSubscriber\Quiz;

use App\Entity\Quiz\Quiz;
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

    public function __construct(UserProvider $userProvider, ResponseManager $responseManager)
    {
        $this->userProvider = $userProvider;
        $this->responseManager = $responseManager;
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
        }
    }
}
