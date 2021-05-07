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

use App\Entity\Quiz\Response;
use App\Entity\User\User;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class ResponseRepository extends EntityRepository
{
    /** @param string|int $uuid */
    public function findOneByUuid($uuid): ?Response
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }

    /** @param int|string $studentId */
    public function createStudentResponseGrid($studentId): QueryBuilder
    {
        $qb = $this->createQueryBuilder('response');

        return $qb
            ->select('response', 'quiz')
            ->where($qb->expr()->eq('response.student', ':student'))
            ->innerJoin('response.quiz', 'quiz')
            ->setParameter('student', $studentId);
    }

    /** @param int|string $studentId */
    public function createFinishedResponseGrid($studentId): QueryBuilder
    {
        $qb = $this->createQueryBuilder('response');

        return $qb
            ->select('response', 'quiz')
            ->where($qb->expr()->eq('response.student', ':student'))
            ->andWhere($qb->expr()->isNotNull('response.finishDate'))
            ->innerJoin('response.quiz', 'quiz')
            ->addOrderBy('response.finishDate', 'DESC')
            ->setParameter('student', $studentId);
    }

    /** @return Response[] */
    public function findUpcomingQuiz(User $student, int $limit): array
    {
        $qb = $this->createQueryBuilder('response');

        return $qb
            ->innerJoin('response.quiz', 'quiz')
            ->where($qb->expr()->eq('response.student', ':student'))
            ->andWhere($qb->expr()->isNull('response.finishDate'))
            ->setParameter('student', $student)
            ->setMaxResults($limit)
            ->addOrderBy('quiz.validFrom', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /** @return Response[] */
    public function findFinishedQuiz(User $student, int $limit): array
    {
        $qb = $this->createQueryBuilder('response');

        return $qb
            ->where($qb->expr()->eq('response.student', ':student'))
            ->andWhere($qb->expr()->isNotNull('response.finishDate'))
            ->setParameter('student', $student)
            ->setMaxResults($limit)
            ->addOrderBy('response.finishDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
