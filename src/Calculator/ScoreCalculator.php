<?php

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
