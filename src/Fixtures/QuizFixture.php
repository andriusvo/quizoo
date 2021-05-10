<?php

declare(strict_types=1);

namespace App\Fixtures;

use App\Entity\Group\StudentGroup;
use App\Entity\Quiz\Quiz;
use App\Entity\Subject\Subject;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class QuizFixture extends AbstractFixture
{
    /** @var FactoryInterface */
    private $factory;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, FactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
    }

    public function load(array $options): void
    {
        foreach ($options['quizzes'] as $quizData) {
            /** @var Quiz $quiz */
            $quiz = $this->factory->createNew();

            $quiz->setValidFrom(new \DateTime($quizData['validFrom']));
            $quiz->setValidTo(new \DateTime($quizData['validTo']));
            $quiz->setCode($quizData['code']);
            $quiz->setTitle($quizData['title']);
            $owner = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $quizData['owner']]);
            $quiz->setOwner($owner);

            $subject = $this->entityManager->getRepository(Subject::class)->findOneBy(['code' => $quizData['subject']]);
            $quiz->setSubject($subject);

            foreach ($quizData['groups'] as $groupCode) {
                $studentGroup = $this
                    ->entityManager
                    ->getRepository(StudentGroup::class)
                    ->findOneBy(['code' => $groupCode]);

                $quiz->addGroup($studentGroup);
            }

            $this->entityManager->persist($quiz);
        }

        $this->entityManager->flush();
    }

    public function getName(): string
    {
        return 'app_quiz';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('quizzes')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('validFrom')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('validTo')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('subject')->cannotBeEmpty()->end()
                        ->scalarNode('code')->cannotBeEmpty()->end()
                        ->scalarNode('owner')->cannotBeEmpty()->end()
                        ->scalarNode('title')->cannotBeEmpty()->end()
                        ->arrayNode('groups')
                            ->scalarPrototype()
                        ->end();
    }
}
