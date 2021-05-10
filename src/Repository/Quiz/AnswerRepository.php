<?php

declare(strict_types=1);

namespace App\Repository\Quiz;

use App\Entity\Quiz\Question;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class AnswerRepository extends EntityRepository
{
    public function createQueryBuilderByQuestion(Question $question): QueryBuilder
    {
        return $this
            ->createQueryBuilder('answer')
            ->where('answer.question = :question')
            ->setParameter('question', $question);
    }
}
