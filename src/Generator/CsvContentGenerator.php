<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\Group\StudentGroup;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Response;
use App\Entity\User\User;
use App\Model\DTO\ResultsDTO;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CsvContentGenerator
{
    /** @var RepositoryInterface */
    private $responseRepository;

    public function __construct(RepositoryInterface $responseRepository)
    {
        $this->responseRepository = $responseRepository;
    }

    public function generate(Quiz $quiz, ResultsDTO $results): string
    {
        $rows[] = ['No.', 'Student', 'Score', 'Start date', 'Finish date'];
        $rows[] = [];

        if (null === $results->getStudentGroup()) {
            $rows[] = $this->buildStudentData($quiz, $results->getStudent(), 1);
        }

        if (null === $results->getStudent()) {
            $this->buildGroupData($rows, $quiz, $results->getStudentGroup());
        }

        return implode("\n", $this->prepareData($rows));
    }

    private function buildStudentData(Quiz $quiz, User $student, int $index): array
    {
        /** @var Response|null $response */
        $response = $this->responseRepository->findOneBy(['quiz' => $quiz, 'student' => $student]);

        if (null === $response) {
            throw new NotFoundHttpException();
        }

        $startDate = $response->getStartDate()->format('Y-m-d H:i:s');
        $finishDate = $response->getFinishDate()->format('Y-m-d H:i:s');

        return [$index, $student->getFullName(), $response->getScore(), $startDate, $finishDate];
    }

    /** @param string[] $content */
    private function buildGroupData(array &$content, Quiz $quiz, StudentGroup $studentGroup): void
    {
        $index = 1;

        foreach ($studentGroup->getStudents() as $student) {
            $content[] = $this->buildStudentData($quiz, $student, $index);
            $index++;
        }
    }

    private function prepareData(array $rows): array
    {
        $content = [];

        foreach ($rows as $row) {
            $content[] = implode(',', $row);
        }

        return $content;
    }
}
