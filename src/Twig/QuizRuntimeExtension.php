<?php

/*
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
 */

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Quiz\Quiz;
use App\Entity\User\User;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Twig\Extension\RuntimeExtensionInterface;

class QuizRuntimeExtension implements RuntimeExtensionInterface
{
    /** @var RepositoryInterface */
    private $quizRepository;

    public function __construct(RepositoryInterface $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    /** @return Quiz[] */
    public function findUpcomingQuizzes(User $user, int $limit): array
    {
        return $this->quizRepository->findUpcomingStudentQuiz($user, $limit);
    }
}
