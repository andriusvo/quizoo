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
 * https://www.nfq.lt
 */

declare(strict_types=1);

namespace App\Form\EventSubscriber\Response;

use App\Entity\Quiz\Answer;
use App\Entity\Quiz\ResponseAnswer;
use App\Repository\Quiz\AnswerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ResponseAnswerTypeSubscriber implements EventSubscriberInterface
{
    /** {@inheritdoc} */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::POST_SUBMIT => 'postSubmit',
        ];
    }

    public function preSetData(FormEvent $event): void
    {
        /** @var ResponseAnswer $responseAnswer */
        $responseAnswer = $event->getData();
        $form = $event->getForm();
        $question = $responseAnswer->getQuestion();

        $form->add(
            'selectedAnswers',
            EntityType::class,
            [
                'class' => Answer::class,
                'expanded' => true,
                'label' => false,
                'multiple' => true,
                'query_builder' => function (AnswerRepository $answerRepository) use ($question) {
                    return $answerRepository->createQueryBuilderByQuestion($question);
                },
            ]
        );
    }

    public function postSubmit(FormEvent $event): void
    {
        $form = $event->getForm();

        if (false === $form->isSubmitted() || false === $form->isValid()) {
            return;
        }

        /** @var ResponseAnswer $responseAnswer */
        $responseAnswer = $event->getData();

        $this->calculateResponseAnswerScore($responseAnswer);
        $this->setStartDate($responseAnswer);
    }

    private function calculateResponseAnswerScore(ResponseAnswer $responseAnswer): void
    {
        $correctAnswers = $responseAnswer->getQuestion()->getCorrectAnswers();
        $singleAnswerScore = 100 / $correctAnswers->count();
        $submittedAnswers = $responseAnswer->getSelectedAnswers();
        $correctAnswersCount = \count(array_intersect($correctAnswers->toArray(), $submittedAnswers->toArray()));
        $score = $singleAnswerScore * $correctAnswersCount;

        $responseAnswer->setScore($score);
    }

    private function setStartDate(ResponseAnswer $responseAnswer): void
    {
        $response = $responseAnswer->getResponse();

        if (null === $response->getStartDate()) {
            $response->setStartDate(new \DateTime());
        }
    }
}
