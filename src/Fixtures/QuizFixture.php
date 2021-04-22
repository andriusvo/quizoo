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

use App\Entity\Quiz\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Platform\Bundle\AdminBundle\Model\AdminUser;
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
        foreach ($options['quizes'] as $quizData) {
            /** @var Quiz $quiz */
            $quiz = $this->factory->createNew();

            $quiz->setFinished($quizData['finished']);
            $quiz->setValidFrom(new \DateTime($quizData['validFrom']));
            $quiz->setValidTo(new \DateTime($quizData['validTo']));
            $quiz->setSubject($quizData['subject']);
            $quiz->setCode($quizData['code']);
            $quiz->setTitle($quizData['title']);
            $quiz->setOwner($this->entityManager->getRepository(AdminUser::class)->findOneBy([]));

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
                ->arrayNode('quizes')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('validFrom')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('validTo')->isRequired()->cannotBeEmpty()->end()
                        ->booleanNode('finished')->defaultFalse()->end()
                        ->scalarNode('subject')->cannotBeEmpty()->end()
                        ->scalarNode('code')->cannotBeEmpty()->end()
                        ->scalarNode('title')->cannotBeEmpty()->end();
    }
}
