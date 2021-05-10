<?php

declare(strict_types=1);

namespace App\Fixtures;

use App\Entity\Subject\Subject;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class SubjectFixture extends AbstractFixture
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var FactoryInterface */
    private $factory;

    public function __construct(EntityManagerInterface $entityManager, FactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
    }

    public function load(array $options): void
    {
        foreach ($options['subjects'] as $subjectData) {
            /** @var Subject $subject */
            $subject = $this->factory->createNew();
            $subject->setCode($subjectData['code']);
            $subject->setTitle($subjectData['title']);

            $supervisor = $this
                ->entityManager
                ->getRepository(User::class)
                ->findOneBy(['username' => $subjectData['supervisor']]);

            $subject->setSupervisor($supervisor);

            $this->entityManager->persist($subject);
        }

        $this->entityManager->flush();
    }

    public function getName(): string
    {
        return 'app_subject';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('subjects')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('code')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('title')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('supervisor')->isRequired()->cannotBeEmpty()->end();
    }
}
