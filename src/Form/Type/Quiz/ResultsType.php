<?php

declare(strict_types=1);

namespace App\Form\Type\Quiz;

use App\Form\Type\Group\StudentGroupAutocompleteChoiceType;
use App\Form\Type\User\StudentAutocompleteChoiceType;
use App\Model\DTO\ResultsDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultsType extends AbstractType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('studentGroup', StudentGroupAutocompleteChoiceType::class)
            ->add('student', StudentAutocompleteChoiceType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => ResultsDTO::class,
            ]
        );
    }
}
