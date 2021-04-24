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

namespace App\Repository\User;

use App\Entity\User\User;
use Sylius\Bundle\UserBundle\Doctrine\ORM\UserRepository as BaseUserRepository;

class UserRepository extends BaseUserRepository
{
    /** @return User[] */
    public function findByNamePart(string $phrase, ?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('user');
        $qb
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('user.firstName', ':name'),
                    $qb->expr()->like('user.lastName', ':name')
                )
            )
            ->setParameter('name', '%' . $phrase . '%')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
