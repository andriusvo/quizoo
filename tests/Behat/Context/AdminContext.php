<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Testwork\Tester\Result\TestResult;
use Symfony\Component\HttpKernel\KernelInterface;

class AdminContext extends RawMinkContext
{
    /** @var KernelInterface */
    private $kernel;

    /** @var MinkContext */
    private $minkContext;

    /** @var int[] */
    private $windowSize = [1440, 900];

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();

        $this->minkContext = $environment->getContext(MinkContext::class);
    }

    /** @AfterStep */
    public function takeScreenshotAfterFailedStep(AfterStepScope $event): void
    {
        if ($event->getTestResult()->getResultCode() !== TestResult::FAILED) {
            return;
        }

        $driver = $this->getSession()->getDriver();

        if (false === $driver instanceof Selenium2Driver) {
            return;
        }

        $driver->resizeWindow($this->windowSize[0], $this->windowSize[1]);

        $stepText = $event->getStep()->getText();
        $fileName = preg_replace('#[^a-zA-Z0-9._-]#', '', $stepText) . '.png';
        $filePath = $this->kernel->getProjectDir() . '/var/uploads';

        $this->saveScreenshot($fileName, $filePath);

        echo 'Screenshot for ' . $stepText . ' placed in ' . $filePath . DIRECTORY_SEPARATOR . $fileName . PHP_EOL;
    }

    /**
     * @When /^I login in as "([^"]*)"$/
     * @Given /^I am logged in as "([^"]*)"$/
     */
    public function iLoginAsUser(string $name): void
    {
        try {
            $this->getSession()->setBasicAuth($name);
        } catch (UnsupportedDriverActionException $e) {
            $this->visitPath('/');
            $this->getSession()->setCookie('test_auth', $name);
        }
    }

    /** @Then /^I click "([^"]*)" link$/ */
    public function iClickLink(string $text): void
    {
        $this->getSession()->getPage()->find('xpath', \sprintf('//a[text()[contains(., "%s")]]', $text))->click();
    }

    /** @Then /^I should see "([^"]*)" in grid$/ */
    public function iShouldSeeInGrid(string $text): void
    {
        $this->minkContext->assertElementContainsText('.ui.sortable.stackable.celled.table', $text);
    }

    /** @Given /^I should not see "([^"]*)" in grid$/ */
    public function iShouldNotSeeInGrid(string $text): void
    {
        $this->minkContext->assertElementNotContainsText('.ui.sortable.stackable.celled.table', $text);
    }

    /** @Then /^I should see "([^"]*)" flash message$/ */
    public function iShouldSeeFlashMessage(string $text): void
    {
        $this->minkContext->assertElementContainsText('.sylius-flash-message', $text);
    }

    /** @Given /^I am on users page$/ */
    public function iAmOnUsersPage(): void
    {
        $this->minkContext->visit('/admin/users');
    }

    /**  @Given /^I change user name to "([^"]*)"$/ */
    public function iChangeUserNameTo(string $name): void
    {
        $this->minkContext->fillField('Username', $name);
        $this->minkContext->pressButton('Save changes');
    }
}
