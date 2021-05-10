<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class QuestionType extends Constraint
{
    public $message = 'app.question.error.invalid_type';

    /**{ @inheritdoc} */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
