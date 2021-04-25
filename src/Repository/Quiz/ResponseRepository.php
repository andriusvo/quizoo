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

namespace App\Repository\Quiz;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class ResponseRepository extends EntityRepository
{
    public function createStudentResponseGrid(string $studentId): QueryBuilder
    {
        $qb = $this->createQueryBuilder('response');

        return $qb
            ->select('response', 'quiz')
            ->where('response.student = :student')
            ->innerJoin('response.quiz', 'quiz')
            ->setParameter('student', $studentId);
    }
}
