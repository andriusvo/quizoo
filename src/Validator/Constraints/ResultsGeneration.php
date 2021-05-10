<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class ResultsGeneration extends Constraint
{
    public $message = 'app.results.error.invalid_target';

    /**{ @inheritdoc} */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
