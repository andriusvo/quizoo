<?php

declare(strict_types=1);

namespace App\Form\Type\Quiz;

use App\Form\EventSubscriber\Quiz\QuestionType;
use App\Form\EventSubscriber\Quiz\QuizTypeSubscriber;
use App\Form\Type\Group\StudentGroupAutocompleteChoiceType;
use App\Form\Type\Subject\SubjectAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class QuizType extends AbstractResourceType
{
    /** @var QuizTypeSubscriber */
    private $quizTypeSubscriber;

    /** @param array $validationGroups */
    public function __construct(QuizTypeSubscriber $quizTypeSubscriber, string $dataClass, array $validationGroups = [])
    {
        parent::__construct($dataClass, $validationGroups);

        $this->quizTypeSubscriber = $quizTypeSubscriber;
    }

    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber($this->quizTypeSubscriber)
            ->add('title', TextType::class)
            ->add(
                'groups',
                StudentGroupAutocompleteChoiceType::class,
                [
                    'multiple' => true,
                    'required' => true,
                ]
            )
            ->add(
                'subject',
                SubjectAutocompleteChoiceType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'validFrom',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'validTo',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                ]
            )
            ->add('enabled', CheckboxType::class)
            ->add(
                'questions',
                CollectionType::class,
                [
                    'entry_type' => QuestionType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            );
    }
}
