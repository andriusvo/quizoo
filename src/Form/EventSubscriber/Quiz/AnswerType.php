<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber\Quiz;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AnswerType extends AbstractResourceType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', TextType::class)
            ->add('correct', CheckboxType::class);
    }

    public function getBlockPrefix(): string
    {
        return 'app_answer';
    }
}
