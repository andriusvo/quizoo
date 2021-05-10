<?php

declare(strict_types=1);

namespace App\Repository\Quiz;

use App\Entity\Quiz\Response;
use App\Entity\User\User;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class ResponseRepository extends EntityRepository
{
    public function findOneByUuid(string $uuid): ?Response
    {
        $qb = $this->createQueryBuilder('response');

        return $qb
            ->where($qb->expr()->eq('response.uuid', ':uuid'))
            ->andWhere($qb->expr()->isNotNull('response.finishDate'))
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByUuidAndNotFinished(string $uuid): ?Response
    {
        $qb = $this->createQueryBuilder('response');

        return $qb
            ->where($qb->expr()->eq('response.uuid', ':uuid'))
            ->andWhere($qb->expr()->isNull('response.finishDate'))
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();
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
