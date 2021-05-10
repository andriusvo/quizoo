<?php

declare(strict_types=1);

namespace App\Form\Type\Response;

use App\Form\EventSubscriber\Response\ResponseTypeSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ResponseType extends AbstractResourceType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new ResponseTypeSubscriber());
    }
}
