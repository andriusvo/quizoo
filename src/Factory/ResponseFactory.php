<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Response;
use App\Entity\User\User;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ResponseFactory implements FactoryInterface
{
    /** @var FactoryInterface */
    private $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    public function createNew(): Response
    {
        return $this->decoratedFactory->createNew();
    }

    public function createForQuizAndStudent(Quiz $quiz, User $student): Response
    {
        $response = $this->createNew();

        $response->setQuiz($quiz);
        $response->setStudent($student);

        return $response;
    }
}
