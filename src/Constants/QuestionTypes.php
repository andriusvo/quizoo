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

namespace App\Constants;

class QuestionTypes
{
    public const SINGLE_ANSWER = 'single_answer';
    public const MULTIPLE_ANSWER = 'multiple_answer';
    public const FREE_TEXT_ANSWER = 'free_text_answer';

    public const ALL = [
        'app.ui.question.type.single_answer' => self::SINGLE_ANSWER,
        'app.ui.question.type.multiple_answer' => self::MULTIPLE_ANSWER,
        'app.ui.question.type.free_text_answer' => self::FREE_TEXT_ANSWER,
    ];
}
