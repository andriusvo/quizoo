Feature:
  In order to administrate application
  As an administrator
  I need to be able to manager users

  Background:
    Given There is a locale
    And There is a user "administrator"
    And I am logged in as "administrator"

  Scenario: List users
    Given I am on users page
    Then I should see "administrator" in grid
