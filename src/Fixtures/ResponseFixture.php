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

namespace App\Fixtures;

use App\Entity\Quiz\Answer;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Response;
use App\Entity\Quiz\ResponseAnswer;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ResponseFixture extends AbstractFixture
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var FactoryInterface */
    private $responseFactory;

    /** @var FactoryInterface */
    private $responseAnswerFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        FactoryInterface $responseFactory,
        FactoryInterface $responseAnswerFactory
    ) {
        $this->responseFactory = $responseFactory;
        $this->entityManager = $entityManager;
        $this->responseAnswerFactory = $responseAnswerFactory;
    }

    public function load(array $options): void
    {
        foreach ($options['responses'] as $responseData) {
            /** @var Response $response */
            $response = $this->responseFactory->createNew();
            $response->setScore($responseData['score']);
            $response->setStartDate(new \DateTime($responseData['startDate']));
            $response->setFinishDate(new \DateTime($responseData['finishDate']));

            $student = $this
                ->entityManager
                ->getRepository(User::class)
                ->findOneBy(['username' => $responseData['student']]);

            $response->setStudent($student);

            $quiz = $this
                ->entityManager
                ->getRepository(Quiz::class)
                ->findOneBy(['code' => $responseData['quiz']]);

            $response->setQuiz($quiz);

            foreach ($responseData['answers'] as $answerData) {
                /** @var ResponseAnswer $answer */
                $answer = $this->responseAnswerFactory->createNew();
                $answer->setCorrect($answerData['correct']);
                $selectedAnswer = $this
                    ->entityManager
                    ->getRepository(Answer::class)
                    ->findOneBy(['value' => $answerData['selectedAnswer']]);

                $answer->addSelectedAnswer($selectedAnswer);
                $response->addAnswer($answer);
            }

            $this->entityManager->persist($response);
        }

        $this->entityManager->flush();
    }

    public function getName(): string
    {
        return 'app_response';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('responses')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                        ->arrayPrototype()
                            ->children()
                            ->scalarNode('startDate')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('finishDate')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('score')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('student')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('quiz')->isRequired()->cannotBeEmpty()->end()
                            ->arrayNode('answers')
                                ->requiresAtLeastOneElement()
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('selectedAnswer')->cannotBeEmpty()->end()
                                        ->booleanNode('correct')->isRequired()->end();
    }
}
