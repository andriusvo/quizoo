<?php

declare(strict_types=1);

namespace App\Fixtures;

use App\Constants\AuthorizationRoles;
use App\Entity\Group\StudentGroup;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Rbac\Model\Role;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class UserFixture extends AbstractFixture
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var FactoryInterface */
    private $factory;

    public function __construct(EntityManagerInterface $entityManager, FactoryInterface $factory)
    {
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    public function load(array $options): void
    {
        foreach ($options['users'] as $userData) {
            /** @var User $user */
            $user = $this->factory->createNew();

            $user->setEmail($userData['email']);
            $user->setPlainPassword($userData['password']);
            $user->setEnabled($userData['enabled']);
            $user->setFirstName($userData['first_name']);
            $user->setLastName($userData['last_name']);
            $user->setUsername($userData['username'] ?? $userData['email']);
            $user->setLocaleCode($userData['locale']);

            foreach ($userData['roles'] as $roleCode) {
                $role = $this->entityManager->getRepository(Role::class)->findOneBy(['code' => $roleCode]);
                $user->addAuthorizationRole($role);
            }

            if (isset($userData['group'])) {
                /** @var StudentGroup $studentGroup */
                $studentGroup = $this
                    ->entityManager
                    ->getRepository(StudentGroup::class)
                    ->findOneBy(['code' => $userData['group']]);

                $studentGroup->addStudent($user);
            }

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }

    public function getName(): string
    {
        return 'app_user';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('users')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('email')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('password')->isRequired()->cannotBeEmpty()->end()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->arrayNode('roles')
                            ->defaultValue([AuthorizationRoles::ROLE_SUPERADMIN])
                            ->requiresAtLeastOneElement()
                            ->scalarPrototype()->cannotBeEmpty()->end()
                        ->end()
                        ->scalarNode('first_name')->defaultNull()->end()
                        ->scalarNode('group')->defaultNull()->end()
                        ->scalarNode('last_name')->defaultNull()->end()
                        ->scalarNode('username')->defaultNull()->end()
                        ->scalarNode('locale')->defaultValue('en')->end()
        ;
    }
}
