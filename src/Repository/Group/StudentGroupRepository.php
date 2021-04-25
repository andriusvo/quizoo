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

namespace App\Repository\Group;

use App\Entity\Group\StudentGroup;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class StudentGroupRepository extends EntityRepository
{
    public function createForGrid(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('student_group');

        $qb
            ->select('student_group', 'student')
            ->leftJoin(
                'student_group.students',
                'student',
            );

        return $qb;
    }

    /** @return StudentGroup[] */
    public function findByNamePart(string $phrase, ?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('student_group');
        $qb
            ->andWhere('student_group.code LIKE :code')
            ->setParameter('code', '%' . $phrase . '%')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
