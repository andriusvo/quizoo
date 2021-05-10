<?php

declare(strict_types=1);

namespace App\Repository\Quiz;

use App\Entity\Quiz\ResponseAnswer;
use Doctrine\ORM\Query\Expr\Join;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class ResponseAnswerRepository extends EntityRepository
{
    /**
     * @param string|int $uuid
     * @param string|int $id
     */
    public function findOneByUuidAndId($uuid, $id): ?ResponseAnswer
    {
        $qb = $this->createQueryBuilder('response_answer');

        return $qb
            ->innerJoin(
                'response_answer.response',
                'response',
                Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('response.uuid', ':uuid'),
                    $qb->expr()->isNull('response.finishDate')
                )
            )
            ->where($qb->expr()->eq('response_answer.id', ':id'))
            ->setParameters(
                [
                    'id' => $id,
                    'uuid' => $uuid,
                ]
            )
            ->getQuery()
            ->getOneOrNullResult();
    }
}
