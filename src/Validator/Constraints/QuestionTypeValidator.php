<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Constants\QuestionTypes;
use App\Entity\Quiz\Question;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class QuestionTypeValidator extends ConstraintValidator
{
    /** {@inheritdoc} */
    public function validate($value, Constraint $constraint): void
    {
        /** @var Question $value */
        Assert::isInstanceOf($value, Question::class);

        if ($value->getAnswers()->count() === 0) {
            return;
        }

        switch ($value->getType()) {
            case QuestionTypes::SINGLE_ANSWER:
                $this->validateSingleAnswer($value, $constraint);
                break;
            case QuestionTypes::MULTIPLE_ANSWER:
                $this->validateMultipleAnswer($value, $constraint);
                break;
            default:
                throw new BadRequestHttpException();
        }
    }

    private function validateSingleAnswer(Question $question, Constraint $constraint): void
    {
        if ($question->countCorrectAnswers() !== 1) {
            $this->context->addViolation($constraint->message);
        }
    }

    private function validateMultipleAnswer(Question $question, Constraint $constraint): void
    {
        if ($question->countCorrectAnswers() <= 1) {
            $this->context->addViolation($constraint->message);
        }
    }
}
