<?php

declare(strict_types=1);

namespace App\Repository\Subject;

use App\Constants\AuthorizationRoles;
use App\Entity\Subject\Subject;
use App\Entity\User\User;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class SubjectRepository extends EntityRepository
{
    public function createForGrid(User $user): QueryBuilder
    {
        $qb = $this->createQueryBuilder('subject');

        $qb
            ->select('subject', 'supervisor')
            ->innerJoin('subject.supervisor', 'supervisor');

        if (false === $user->hasRole(AuthorizationRoles::ROLE_ADMIN)) {
            $qb
                ->andWhere($qb->expr()->eq('subject.supervisor', ':supervisor'))
                ->setParameter('supervisor', $user);
        }

        return $qb;
    }

    /** @return Subject[] */
    public function findByNamePart(string $phrase, User $user, ?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('subject');
        $qb
            ->andWhere('subject.title LIKE :title')
            ->setParameter('title', '%' . $phrase . '%')
            ->setMaxResults($limit);

        if ($user->hasRole(AuthorizationRoles::ROLE_TEACHER)) {
            $qb
                ->andWhere('subject.supervisor = :supervisor')
                ->setParameter('supervisor', $user);
        }

        return $qb->getQuery()->getResult();
    }
}
