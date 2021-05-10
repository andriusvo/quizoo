<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Constants\AuthorizationRoles;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Response;
use App\Entity\Subject\Subject;
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
     * @Given /^There is a user "(?P<name>[^"]*)"$/
     * @Given /^There is a user "(?P<name>[^"]*)" with role "(?P<role>[^"]*)"$/
     * @Given /^There is a user "(?P<name>[^"]*)" with locale "(?P<locale>[^"]*)"$/
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
        $object->setFirstName($name);
        $object->setLastName($name);
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

    /** @Given /^There is a response for student "([^"]*)" and quiz "([^"]*)"$/ */
    public function thereIsAResponseForStudent(string $student, string $quiz): Response
    {
        if (isset($this->references[Response::class][$student])) {
            return $this->references[Response::class][$student];
        }

        $object = new Response();
        $object->setScore(100);
        $object->setStudent($this->references[User::class][$student]);
        $object->setStartDate(new \DateTime());
        $object->setQuiz($this->references[Quiz::class][$quiz]);

        $this->entityManager->persist($object);
        $this->entityManager->flush();

        $this->references[Response::class][$student] = $object;

        return $object;
    }

    /** @Given /^There is a subject "([^"]*)" with supervisor "([^"]*)"$/ */
    public function thereIsASubjectWithSupervisor(string $subject, string $supervisor): Subject
    {
        if (isset($this->references[Subject::class][$subject])) {
            return $this->references[Subject::class][$subject];
        }

        $object = new Subject();
        $object->setCode($subject);
        $object->setSupervisor($this->references[User::class][$supervisor]);
        $object->setTitle($subject);

        $this->entityManager->persist($object);
        $this->entityManager->flush();

        $this->references[Subject::class][$subject] = $object;

        return $object;
    }

    /** @Given /^There is a quiz with title "([^"]*)" for subject "([^"]*)"$/ */
    public function thereIsAQuizWithTitle(string $title, string $subject): Quiz
    {
        if (isset($this->references[Quiz::class][$title])) {
            return $this->references[Quiz::class][$title];
        }

        $object = new Quiz();
        $object->setCode($title);
        $object->setOwner($this->references[User::class]['administrator']);
        $object->setTitle($title);
        $object->setValidFrom(new \DateTime());
        $object->setValidTo(new \DateTime());
        $object->setSubject($this->references[Subject::class][$subject]);

        $this->entityManager->persist($object);
        $this->entityManager->flush();

        $this->references[Quiz::class][$title] = $object;

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
