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

namespace App\Calculator;

use App\Entity\Quiz\ResponseAnswer;

class ScoreCalculator
{
    public function calculate(ResponseAnswer $responseAnswer): int
    {
        $correctAnswers = $responseAnswer->getQuestion()->getCorrectAnswers();
        $singleAnswerScore = 100 / $correctAnswers->count();
        $submittedAnswers = $responseAnswer->getSelectedAnswers();
        $correctAnswersCount = \count(array_intersect($correctAnswers->toArray(), $submittedAnswers->toArray()));

        return $singleAnswerScore * $correctAnswersCount;
    }
}
