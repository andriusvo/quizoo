Feature: Homepage
  In order to administrate application
  As an administrator
  I need to be able to see admin page

  Background:
    Given There is a locale
    And There is a user "administrator"

  Scenario: Open admin page logged in
    Given I am logged in as "administrator"
    When I go to "/"
    Then I should be on "/admin/"
    And I should see "Quizoo"

  Scenario: Open admin page not logged in
    When I go to "/"
    Then I should be on "/login"
    And I should see a "form" element
