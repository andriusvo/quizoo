<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Model\DTO\ResultsDTO;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class ResultsGenerationValidator extends ConstraintValidator
{
    /** {@inheritdoc} */
    public function validate($value, Constraint $constraint): void
    {
        /** @var ResultsDTO $value */
        Assert::isInstanceOf($value, ResultsDTO::class);

        if (null === $value->getStudentGroup() && null === $value->getStudent()) {
            $this->context->addViolation($constraint->message);
        }
    }
}
