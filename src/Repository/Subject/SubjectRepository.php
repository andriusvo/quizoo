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

namespace App\Repository\Subject;

use App\Constants\AuthorizationRoles;
use App\Entity\Subject\Subject;
use App\Entity\User\User;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class SubjectRepository extends EntityRepository
{
    public function createForGrid(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('subject');

        $qb
            ->select('subject', 'supervisor')
            ->innerJoin('subject.supervisor', 'supervisor');

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
