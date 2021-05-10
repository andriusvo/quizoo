<?php

declare(strict_types=1);

namespace App\Fixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class LocaleFixture extends AbstractFixture
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
        foreach ($options['locales'] as $localeData) {
            /** @var LocaleInterface $locale */
            $locale = $this->factory->createNew();
            $locale->setCode($localeData['code']);

            $this->manager->persist($locale);
        }

        $this->manager->flush();
    }

    public function getName(): string
    {
        return 'app_locale';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('locales')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('code')->isRequired()->cannotBeEmpty()->end();
    }
}
