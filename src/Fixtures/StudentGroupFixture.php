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

use App\Entity\Group\StudentGroup;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class StudentGroupFixture extends AbstractFixture
{
    /** @var ObjectManager */
    private $manager;

    /** @var FactoryInterface */
    private $factory;

    public function __construct(ObjectManager $manager, FactoryInterface $factory)
    {
        $this->manager = $manager;
        $this->factory = $factory;
    }

    public function load(array $options): void
    {
        foreach ($options['groups'] as $studentGroupData) {
            /** @var StudentGroup $studentGroup */
            $studentGroup = $this->factory->createNew();
            $studentGroup->setCode($studentGroupData['code']);

            $this->manager->persist($studentGroup);
        }

        $this->manager->flush();
    }

    public function getName(): string
    {
        return 'app_student_group';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('groups')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('code')->isRequired()->cannotBeEmpty()->end();
    }
}
