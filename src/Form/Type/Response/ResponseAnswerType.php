<?php

declare(strict_types=1);

namespace App\Form\Type\Response;

use App\Form\EventSubscriber\Response\ResponseAnswerTypeSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class ResponseAnswerType extends AbstractResourceType
{
    /** @var ResponseAnswerTypeSubscriber */
    private $responseAnswerTypeSubscriber;

    /** {@inheritdoc} */
    public function __construct(
        ResponseAnswerTypeSubscriber $responseAnswerTypeSubscriber,
        string $dataClass,
        array $validationGroups = []
    ) {
        parent::__construct($dataClass, $validationGroups);
        $this->responseAnswerTypeSubscriber = $responseAnswerTypeSubscriber;
    }

    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventSubscriber($this->responseAnswerTypeSubscriber);
    }
}
