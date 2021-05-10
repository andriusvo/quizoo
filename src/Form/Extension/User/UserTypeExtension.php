<?php

declare(strict_types=1);

namespace App\Form\Extension\User;

use Platform\Bundle\AdminBundle\Form\Type\AdminUserType;
use Sylius\Bundle\RbacBundle\Form\Type\RoleChoiceType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class UserTypeExtension extends AbstractTypeExtension
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'authorizationRoles',
                RoleChoiceType::class,
                [
                    'multiple' => true,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [AdminUserType::class];
    }
}
