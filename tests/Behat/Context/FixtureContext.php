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

namespace App\Tests\Behat\Context;

use App\Constants\AuthorizationRoles;
use App\Entity\User\User;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Locale\Model\Locale;
use Sylius\Component\Rbac\Model\Role;
use Symfony\Component\HttpKernel\KernelInterface;

class FixtureContext extends RawMinkContext
{
    /** @var array */
    private $references = [];

    /** @var KernelInterface */
    private $kernel;

    /** @var bool */
    private static $initialized = false;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(KernelInterface $kernel, EntityManagerInterface $entityManager)
    {
        $this->kernel = $kernel;
        $this->entityManager = $entityManager;
    }

    /** @BeforeScenario */
    public function initializeDatabase(): void
    {
        if (self::$initialized) {
            return;
        }

        $this->dropAndCreateDatabase();
        $this->executeMigrations();
        $this->initializeRbacRoles();

        self::$initialized = true;
    }

    /** @AfterScenario */
    public function afterScenario(): void
    {
        $excluded = ['sylius_role', 'sylius_role_permission', 'sylius_permission'];

        (new ORMPurger($this->entityManager, $excluded))->purge();
    }

    /**
     * @Given /^There is an admin user "(?P<name>[^"]*)"$/
     * @Given /^There is an admin user "(?P<name>[^"]*)" with role "(?P<role>[^"]*)"$/
     * @Given /^There is an admin user "(?P<name>[^"]*)" with locale "(?P<locale>[^"]*)"$/
     */
    public function thereIsAnAdminUser(
        string $name,
        string $role = AuthorizationRoles::ROLE_SUPERADMIN,
        string $locale = null
    ): User {
        if (isset($this->references[User::class][$name])) {
            return $this->references[User::class][$name];
        }

        $object = new User();
        $object->setUsername($name);
        $object->setEmail($name);
        $object->setPlainPassword($name);
        $object->setLocaleCode($this->thereIsALocale($locale)->getCode());
        $object->setEnabled(true);

        $authRole = $this->entityManager->getRepository(Role::class)->findOneBy(['code' => $role]);
        $object->addAuthorizationRole($authRole);

        $this->entityManager->persist($object);
        $this->entityManager->persist($authRole);
        $this->entityManager->flush();

        $this->references[User::class][$name] = $object;

        return $object;
    }

    /**
     * @Given /^There is a locale$/
     * @Given /^There is a locale "([^"]*)"$/
     */
    public function thereIsALocale(string $locale = null): Locale
    {
        if (null === $locale) {
            $locale = $this->kernel->getContainer()->getParameter('locale');
        }

        if (isset($this->references[Locale::class][$locale])) {
            return $this->references[Locale::class][$locale];
        }

        $object = new Locale();
        $object->setCode($locale);

        $this->entityManager->persist($object);
        $this->entityManager->flush();

        $this->references[Locale::class][$locale] = $object;

        return $object;
    }

    /**
     * Drop and create DB
     */
    private function dropAndCreateDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        $schema = $connection->getSchemaManager();

        $schema->dropAndCreateDatabase($connection->getDatabase());

        $connection->close();
    }

    /**
     * Exec migrations
     */
    private function executeMigrations(): void
    {
        exec(sprintf('php "%s/bin/console" doctrine:migrations:migrate -n -e test', $this->kernel->getProjectDir()));
    }

    /**
     * Init rbac roles
     */
    private function initializeRbacRoles(): void
    {
        exec(sprintf('php "%s/bin/console" sylius:rbac:init -e test', $this->kernel->getProjectDir()));
    }
}
