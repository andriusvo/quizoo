<?php

declare(strict_types=1);

namespace App\Form\Type\Subject;

use App\Form\Type\User\UserAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SubjectType extends AbstractResourceType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('title', TextType::class)
            ->add(
                'supervisor',
                UserAutocompleteChoiceType::class,
                [
                    'required' => true,
                ]
            );
    }
}
