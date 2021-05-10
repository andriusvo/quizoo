<?php

declare(strict_types=1);

namespace App\Constants;

class QuestionTypes
{
    public const SINGLE_ANSWER = 'single_answer';
    public const MULTIPLE_ANSWER = 'multiple_answer';

    public const ALL = [
        'app.ui.question.type.single_answer' => self::SINGLE_ANSWER,
        'app.ui.question.type.multiple_answer' => self::MULTIPLE_ANSWER,
    ];
}
