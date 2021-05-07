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

namespace App\Repository\Quiz;

use App\Entity\Quiz\Question;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class AnswerRepository extends EntityRepository
{
    public function createQueryBuilderByQuestion(Question $question): QueryBuilder
    {
        return $this
            ->createQueryBuilder('answer')
            ->where('answer.question = :question')
            ->setParameter('question', $question);
    }
}
