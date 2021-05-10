<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber\Response;

use App\Entity\Quiz\Response;
use App\Entity\Quiz\ResponseAnswer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ResponseTypeSubscriber implements EventSubscriberInterface
{
    /** {@inheritdoc} */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'postSubmit',
        ];
    }

    public function postSubmit(FormEvent $event): void
    {
        $form = $event->getForm();

        if (false === $form->isSubmitted() || false === $form->isValid()) {
            return;
        }

        /** @var Response $response */
        $response = $event->getData();
        $response->setFinishDate(new \DateTime());

        $score = 0;

        /** @var ResponseAnswer $answer */
        foreach ($response->getAnswers() as $answer) {
            $score += $answer->getScore();
        }

        $response->setScore($score);
    }
}
