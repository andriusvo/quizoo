<?php

declare(strict_types=1);

namespace App\Repository\Quiz;

use App\Constants\AuthorizationRoles;
use App\Entity\Quiz\Quiz;
use App\Entity\User\User;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class QuizRepository extends EntityRepository
{
    public function createForGrid(User $user): QueryBuilder
    {
        $qb = $this->createQueryBuilderWithUser($user);

        $qb
            ->select('quiz', 'studentGroup')
            ->leftJoin('quiz.groups', 'studentGroup');

        return $qb;
    }

    /** @return Quiz[] */
    public function findLatest(User $user, int $limit): array
    {
        $qb = $this->createQueryBuilderWithUser($user);

        return $qb
            ->addOrderBy('quiz.validTo', 'DESC')
            ->andWhere($qb->expr()->lt('quiz.validTo', ':now'))
            ->setParameter('now', new \DateTime('now'))
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /** @return Quiz[] */
    public function findUpcoming(User $user, int $limit): array
    {
        $qb = $this->createQueryBuilderWithUser($user);

        return $qb
            ->andWhere($qb->expr()->gt('quiz.validTo', ':now'))
            ->setParameter('now', new \DateTime('now'))
            ->addOrderBy('quiz.validTo', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    private function createQueryBuilderWithUser(User $user): QueryBuilder
    {
        $qb = $this->createQueryBuilder('quiz');

        if (false === $user->hasRole(AuthorizationRoles::ROLE_ADMIN)) {
            $qb
                ->andWhere($qb->expr()->eq('quiz.owner', ':owner'))
                ->setParameter('owner', $user);
        }

        return $qb;
    }
}
