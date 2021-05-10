<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\User;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\UserBundle\Doctrine\ORM\UserRepository as BaseUserRepository;

class UserRepository extends BaseUserRepository
{
    /** @param string[] $roles */
    public function createQueryBuilderByRoles(array $roles): QueryBuilder
    {
        $qb = $this->createQueryBuilder('user');
        $qb
            ->innerJoin(
                'user.authorizationRoles',
                'role',
                Join::WITH,
                $qb->expr()->in('role.code', ':roles')
            )
            ->setParameter('roles', $roles);

        return $qb;
    }

    /** @return User[] */
    public function findByNamePart(string $phrase, string $role, ?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('user');
        $qb
            ->innerJoin(
                'user.authorizationRoles',
                'role',
                Join::WITH,
                $qb->expr()->eq('role.code', ':role')
            )
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('user.firstName', ':name'),
                    $qb->expr()->like('user.lastName', ':name')
                )
            )
            ->setParameter('role', $role)
            ->setParameter('name', '%' . $phrase . '%')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
