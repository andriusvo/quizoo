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

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\RawMinkContext;

class GridContext extends RawMinkContext
{
    /** @var NodeElement|null */
    private $row;

    /** @Given /^I see grid row with text "([^"]*)" in column "([^"]*)"/ */
    public function iSeeGridRowWithTextInColumn(string $value, string $column): void
    {
        $this->row = $this->getRowByColumnsValues([$column], [$value]);
    }

    /** @Given /^I see grid row:$/ */
    public function iSeeGridRow(TableNode $tableNode): void
    {
        $data = $tableNode->getRowsHash();
        $columns = \array_keys($data);
        $values = \array_values($data);

        $this->row = $this->getRowByColumnsValues($columns, $values);
    }

    /** @Given /^This row has text "([^"]*)" in column "([^"]*)"$/ */
    public function thisRowHasTextInColumn(string $text, string $columnName): void
    {
        $columnValue = \trim($this->getRowColumn($this->row, $columnName)->getHtml());

        if ($columnValue !== $text) {
            throw new ExpectationException(
                sprintf('"%s" does not match "%s"', $text, $columnValue),
                $this->getSession()->getDriver()
            );
        }
    }

    /** @Then /^I should see "([^"]*)" button in this row$/ */
    public function iShouldSeeButtonInThisRow(string $label): void
    {
        $column = $this->getRowColumn($this->row, 'Actions');
        $button = $column->find('xpath', "//button[normalize-space(text())='{$label}']");

        if (null === $button) {
            throw new ExpectationException(
                sprintf('Button "%s" was not found', $label),
                $this->getSession()->getDriver()
            );
        }
    }

    /** @Then /^I should not see "([^"]*)" button in this row$/ */
    public function iShouldNotSeeButtonInThisRow(string $label): void
    {
        $column = $this->getRowColumn($this->row, 'Actions');
        $button = $column->find('xpath', "//button[normalize-space(text())='{$label}']");

        if (null !== $button) {
            throw new ExpectationException(
                sprintf('Button "%s" was found', $label),
                $this->getSession()->getDriver()
            );
        }
    }

    /** @Then /^I follow this row link "([^"]*)"$/ */
    public function iFollowThisLink(string $label): void
    {
        $column = $this->getRowColumn($this->row, 'Actions');
        $link = $column->find('xpath', "//a[text()[contains(., '{$label}')]][1]");

        $link->click();
    }

    /** @Given /^I see empty grid row column "([^"]*)"$/ */
    public function iSeeEmptyGridRowColumn(string $columnName): void
    {
        $column = $this->getRowColumn($this->row, $columnName);

        if (false === empty($column->getHtml())) {
            throw new \Exception(sprintf('Grid row column contains: %s', $column->getHtml()));
        }
    }

    /** @Then /^I edit "([^"]*)" from grid$/ */
    public function iEditFromGrid(string $text): void
    {
        $this->getSession()->getPage()->find(
            'xpath',
            "//tr[@class=\"item\"]/td[text()[contains(., \"{$text}\")]]/../"
            . 'td/descendant::a[text()[contains(., "Edit")]]'
        )->click();
    }

    private function getRowByColumnsValues(array $columnTitle, array $columnValue): NodeElement
    {
        $tds = [];

        foreach ($columnTitle as $key => $title) {
            $tds[] = \sprintf('td[contains(., "%s")]', $columnValue[$key]);
        }

        $row = $this->getSession()->getDriver()->find(
            \sprintf(
                '//*[@id="content"]/div/table/tbody/tr[%s]',
                \implode(' and ', $tds)
            )
        );

        if (false === isset($row[0])) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'Grid row');
        }

        return $row[0];
    }

    private function getRowColumn(NodeElement $row, string $columnTitle): NodeElement
    {
        $columnPosition = $this->getColumnPosition($columnTitle);

        return $row->find('xpath', "td[{$columnPosition}]");
    }

    private function getColumnPosition(string $title): int
    {
        $column = $this->getSession()->getDriver()->find(
            "//*[@id='content']/div/div/table/thead/tr/th[contains(., '{$title}')]/preceding-sibling::th"
        );

        return \count($column) + 1;
    }
}
