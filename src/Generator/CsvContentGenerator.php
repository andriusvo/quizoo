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

namespace App\Generator;

use App\Entity\Group\StudentGroup;
use App\Entity\Quiz\Quiz;
use App\Entity\User\User;
use App\Model\ResultsDTO;

class CsvContentGenerator
{
    public function generate(Quiz $quiz, ResultsDTO $results): string
    {
        $data = [];

        if (null === $results->getStudentGroup()) {
            $data = $this->buildStudentData($quiz, $results->getStudent());
        }

        if (null === $results->getStudent()) {
            $data = $this->buildGroupData($quiz, $results->getStudentGroup());
        }

        $rows[] = implode(',', $data);

        return implode("\n", $rows);
    }

    private function buildStudentData(Quiz $quiz, User $student): array
    {
        $content = [$quiz->getId(), $quiz->getCode(), $student->getFullName()];

        return $content;
    }

    private function buildGroupData(Quiz $quiz, StudentGroup $studentGroup): array
    {
        $content = [];

        foreach ($studentGroup->getStudents() as $student) {
            $content[] = $this->buildStudentData($quiz, $student);
        }

        return $content;
    }
}
