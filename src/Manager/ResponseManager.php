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
 * https://www.nfq.lt
 */

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Group\StudentGroup;
use App\Entity\Quiz\Quiz;
use App\Entity\User\User;
use App\Factory\ResponseAnswerFactory;
use App\Factory\ResponseFactory;
use Doctrine\ORM\EntityManagerInterface;

class ResponseManager
{
    /** @var ResponseFactory */
    private $responseFactory;

    /** @var ResponseAnswerFactory */
    private $responseAnswerFactory;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        ResponseFactory $responseFactory,
        ResponseAnswerFactory $responseAnswerFactory,
        EntityManagerInterface $entityManager
    ) {
        $this->responseFactory = $responseFactory;
        $this->responseAnswerFactory = $responseAnswerFactory;
        $this->entityManager = $entityManager;
    }

    public function createResponsesForGroups(Quiz $quiz): void
    {
        /** @var StudentGroup $group */
        foreach ($quiz->getGroups() as $group) {
            /** @var User $student */
            foreach ($group->getStudents() as $student) {
                $this->createResponse($quiz, $student);
            }
        }

        $this->entityManager->flush();
    }

    private function createResponse(Quiz $quiz, User $student): void
    {
        $response = $this->responseFactory->createForQuizAndStudent($quiz, $student);

        foreach ($quiz->getQuestions() as $question) {
            $responseAnswer = $this->responseAnswerFactory->createForQuestion($question);
            $response->addAnswer($responseAnswer);
        }

        $this->entityManager->persist($response);
    }
}
