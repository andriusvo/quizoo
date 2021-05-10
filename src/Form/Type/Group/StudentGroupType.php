<?php

declare(strict_types=1);

namespace App\Form\Type\Group;

use App\Form\Type\User\StudentAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class StudentGroupType extends AbstractResourceType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add(
                'students',
                StudentAutocompleteChoiceType::class,
                [
                    'multiple' => true,
                    'required' => true,
                ]
            );
    }
}
