<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Quiz\Response;
use App\Entity\User\User;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Twig\Extension\RuntimeExtensionInterface;

class QuizRuntimeExtension implements RuntimeExtensionInterface
{
    /** @var RepositoryInterface */
    private $responseRepository;

    public function __construct(RepositoryInterface $responseRepository)
    {
        $this->responseRepository = $responseRepository;
    }

    /** @return Response[] */
    public function findUpcomingQuizzes(User $user, int $limit): array
    {
        return $this->responseRepository->findUpcomingQuiz($user, $limit);
    }

    /** @return Response[] */
    public function findFinishedQuizzes(User $user, int $limit): array
    {
        return $this->responseRepository->findFinishedQuiz($user, $limit);
    }
}
