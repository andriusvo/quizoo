<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber\Quiz;

use App\Constants\QuestionTypes;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class QuestionType extends AbstractResourceType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => QuestionTypes::ALL,
                ]
            )
            ->add(
                'answers',
                CollectionType::class,
                [
                    'entry_type' => AnswerType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            );
    }

    public function getBlockPrefix(): string
    {
        return 'app_question';
    }
}
