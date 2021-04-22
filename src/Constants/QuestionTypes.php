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
    public const BOOLEAN_ANSWER = 'boolean_answer';
    public const FREE_TEXT_ANSWER = 'free_text_answer';

    public const ALL = [
        'app.question.type.single' => self::SINGLE_ANSWER,
        'app.question.type.multiple' => self::MULTIPLE_ANSWER,
        'app.question.type.boolean' => self::BOOLEAN_ANSWER,
        'app.question.type.free_text' => self::FREE_TEXT_ANSWER,
    ];
}
