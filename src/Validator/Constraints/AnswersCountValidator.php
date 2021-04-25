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

namespace App\Validator\Constraints;

use App\Constants\QuestionTypes;
use App\Entity\Quiz\Question;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class AnswersCountValidator extends ConstraintValidator
{
    /** {@inheritdoc} */
    public function validate($value, Constraint $constraint): void
    {
        /** @var Question $value */
        Assert::isInstanceOf($value, Question::class);

        switch ($value->getType()) {
            case QuestionTypes::SINGLE_ANSWER:
            case QuestionTypes::MULTIPLE_ANSWER:
                $this->validateAnswersCount($value, $constraint);
                break;
            case QuestionTypes::FREE_TEXT_ANSWER:
                $this->validateFreeTextAnswersCount($value, $constraint);
                break;
            default:
                throw new BadRequestHttpException();
        }
    }

    private function validateAnswersCount(Question $question, Constraint $constraint): void
    {
        if ($question->getAnswers()->isEmpty()) {
            $this->context->addViolation($constraint->message);
        }
    }

    private function validateFreeTextAnswersCount(Question $question, Constraint $constraint): void
    {
        if ($question->countCorrectAnswers() > 0) {
            $this->context->addViolation($constraint->message);
        }
    }
}
