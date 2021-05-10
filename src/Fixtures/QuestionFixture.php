<?php

declare(strict_types=1);

namespace App\Fixtures;

use App\Constants\QuestionTypes;
use App\Entity\Quiz\Answer;
use App\Entity\Quiz\Question;
use App\Entity\Quiz\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class QuestionFixture extends AbstractFixture
{
    /** @var FactoryInterface */
    private $questionFactory;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var FactoryInterface */
    private $answerFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        FactoryInterface $questionFactory,
        FactoryInterface $answerFactory
    ) {
        $this->questionFactory = $questionFactory;
        $this->entityManager = $entityManager;
        $this->answerFactory = $answerFactory;
    }

    public function load(array $options): void
    {
        foreach ($options['questions'] as $questionsData) {
            /** @var Question $question */
            $question = $this->questionFactory->createNew();

            $question->setType($questionsData['type']);
            $question->setTitle($questionsData['title']);
            $quiz = $this->entityManager->getRepository(Quiz::class)->findOneBy(['code' => $questionsData['quiz']]);
            $question->setQuiz($quiz);

            foreach ($questionsData['answers'] as $answerData) {
                /** @var Answer $answer */
                $answer = $this->answerFactory->createNew();
                $answer->setValue((string)$answerData['value']);
                $answer->setCorrect($answerData['correct']);

                $question->addAnswer($answer);
            }

            $this->entityManager->persist($question);
        }

        $this->entityManager->flush();
    }

    public function getName(): string
    {
        return 'app_question';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('questions')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('type')->defaultValue(QuestionTypes::SINGLE_ANSWER)->cannotBeEmpty()->end()
                        ->scalarNode('title')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('quiz')->cannotBeEmpty()->end()
                        ->arrayNode('answers')
                            ->requiresAtLeastOneElement()
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('value')->cannotBeEmpty()->end()
                                    ->booleanNode('correct')->isRequired()->end();
    }
}
