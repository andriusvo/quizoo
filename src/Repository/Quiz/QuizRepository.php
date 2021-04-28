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

use App\Constants\AuthorizationRoles;
use App\Entity\User\User;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class QuizRepository extends EntityRepository
{
    public function createForGrid(User $user): QueryBuilder
    {
        $qb = $this->createQueryBuilder('quiz');

        $qb
            ->select('quiz', 'studentGroup')
            ->leftJoin('quiz.groups', 'studentGroup');

        if (false === $user->hasRole(AuthorizationRoles::ROLE_ADMIN)) {
            $qb
                ->andWhere($qb->expr()->eq('quiz.owner', ':owner'))
                ->setParameter('owner', $user);
        }

        return $qb;
    }
}
