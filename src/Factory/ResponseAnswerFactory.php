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

namespace App\Factory;

use App\Entity\Quiz\Question;
use App\Entity\Quiz\ResponseAnswer;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ResponseAnswerFactory implements FactoryInterface
{
    /** @var FactoryInterface */
    private $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    public function createNew(): ResponseAnswer
    {
        return $this->decoratedFactory->createNew();
    }

    public function createForQuestion(Question $question): ResponseAnswer
    {
        $responseAnswer = $this->createNew();

        $responseAnswer->setQuestion($question);

        return $responseAnswer;
    }
}
