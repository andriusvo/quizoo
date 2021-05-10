Feature: Students
  In order to administrate application
  As an administrator
  I need to be able to see students list

  Background:
    Given There is a locale
    And There is a user "administrator"
    And I am logged in as "administrator"
    And There is a user "student" with role "ROLE_STUDENT"
    And There is a subject "maths" with supervisor "administrator"
    And There is a quiz with title "Super quiz" for subject "maths"
    And There is a response for student "student" and quiz "Super quiz"

  Scenario: Administrator should be able to see students
    When I go to "/admin/students"
    Then I see grid row with text "student" in column "First name"
    Then I click "Show responses" link
    Then I see grid row with text "100" in column "Score"
    And I see grid row with text "Super quiz" in column "Quiz"
