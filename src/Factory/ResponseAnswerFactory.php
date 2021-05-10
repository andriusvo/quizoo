<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Quiz\Question;
use App\Entity\Quiz\ResponseAnswer;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ResponseAnswerFactory implements FactoryInterface
{
    /** @var FactoryInterface */
    private $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    public function createNew(): ResponseAnswer
    {
        return $this->decoratedFactory->createNew();
    }

    public function createForQuestion(Question $question): ResponseAnswer
    {
        $responseAnswer = $this->createNew();

        $responseAnswer->setQuestion($question);

        return $responseAnswer;
    }
}
