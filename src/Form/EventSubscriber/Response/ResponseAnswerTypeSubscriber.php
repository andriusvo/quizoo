<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber\Response;

use App\Calculator\ScoreCalculator;
use App\Entity\Quiz\Answer;
use App\Entity\Quiz\ResponseAnswer;
use App\Repository\Quiz\AnswerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ResponseAnswerTypeSubscriber implements EventSubscriberInterface
{
    /** @var ScoreCalculator */
    private $scoreCalculator;

    public function __construct(ScoreCalculator $scoreCalculator)
    {
        $this->scoreCalculator = $scoreCalculator;
    }

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
        $score = $this->scoreCalculator->calculate($responseAnswer);
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
