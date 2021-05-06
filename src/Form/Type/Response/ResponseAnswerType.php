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

namespace App\Form\Type\Response;

use App\Constants\QuestionTypes;
use App\Entity\Quiz\Answer;
use App\Entity\Quiz\ResponseAnswer;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ResponseAnswerType extends AbstractResourceType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                static function (FormEvent $event): void {
                    /** @var ResponseAnswer $responseAnswer */
                    $responseAnswer = $event->getData();
                    $form = $event->getForm();
                    $question = $responseAnswer->getQuestion();
                    $isMultiple = $question->getType() === QuestionTypes::MULTIPLE_ANSWER;

                    $form->add(
                        'selectedAnswers',
                        ChoiceType::class,
                        [
                            'expanded' => true,
                            'multiple' => $isMultiple,
                            'choices' => $question->getAnswers()->toArray(),
                            'choice_label' => function (Answer $answer) {
                                return $answer->getValue();
                            },
                            'label' => false,
                        ]
                    );
                }
            );
    }
}
