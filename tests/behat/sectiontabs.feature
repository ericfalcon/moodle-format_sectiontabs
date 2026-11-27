@format @format_sectiontabs
Feature: Navigate a course in the Tabs and section menu format
  In order to find my way in a course
  As a learner
  I need tabs, a section menu, subsection tabs, previous / next buttons, my progress and a way to resume

  Background:
    Given I enable "subsection" "mod" plugin
    And the following "users" exist:
      | username | firstname | lastname |
      | teacher1 | Teacher   | 1        |
      | student1 | Student   | 1        |
    And the following "courses" exist:
      | fullname | shortname | format      | numsections | initsections | coursedisplay | enablecompletion |
      | Course 1 | C1        | sectiontabs | 3           | 1            | 1             | 1                |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
    And the following "activities" exist:
      | activity   | name          | course | section | completion |
      | subsection | Lessons       | C1     | 1       | 0          |
      | subsection | Exercises     | C1     | 1       | 0          |
      | page       | Lesson page   | C1     | 4       | 1          |
      | page       | Exercise page | C1     | 5       | 1          |

  Scenario: The tabs and the previous / next bar lead from section to section
    When I am on the "Course 1" "Course" page logged in as "student1"
    Then "Section 1" "link" should exist in the ".gtabs" "css_element"
    And "Section 3" "link" should exist in the ".gtabs-side" "css_element"
    And I click on "Section 2" "link" in the ".gtabs" "css_element"
    And I should see "Section 2" in the ".gtabs .gtabs-active" "css_element"
    And I should see "Section 2" in the ".gtabs-side .gtabs-side-active" "css_element"
    And I should see "Section 1" in the ".gnav .gnav-prev" "css_element"
    And I should see "Section 3" in the ".gnav .gnav-next" "css_element"
    And I click on ".gnav .gnav-next" "css_element"
    And I should see "Section 3" in the ".gtabs .gtabs-active" "css_element"
    And ".gnav .gnav-next.d-none" "css_element" should exist

  @javascript @accessibility
  Scenario: The subsections are tabs, and "Next" goes on to the next section after the last one
    When I am on the "Course 1" "Course" page logged in as "student1"
    And I click on "Section 1" "link" in the ".gtabs" "css_element"
    Then I should see "Lessons" in the ".gsubtabs .gsubtabs-active" "css_element"
    And the page should meet accessibility standards
    And I should see "Lesson page" in the "region-main" "region"
    And I should not see "Exercise page" in the "region-main" "region"
    And I click on "Exercises" "link" in the ".gsubtabs" "css_element"
    And I should see "Exercise page" in the "region-main" "region"
    And I should not see "Lesson page" in the "region-main" "region"
    And I should see "Section 2" in the ".gnav .gnav-next" "css_element"
    And I click on ".gnav .gnav-prev" "css_element"
    And I should see "Lesson page" in the "region-main" "region"
    And I click on ".gnav .gnav-next" "css_element"
    And I click on ".gnav .gnav-next" "css_element"
    And I should see "Section 2" in the ".gtabs .gtabs-active" "css_element"

  @javascript
  Scenario: A subsection whose activities are completed is ticked
    When I am on the "Course 1" "Course" page logged in as "student1"
    And I click on "Section 1" "link" in the ".gtabs" "css_element"
    Then ".gsubtabs .gtabs-check" "css_element" should not exist
    And I toggle the manual completion state of "Lesson page"
    And I reload the page
    And ".gsubtabs .gsubtabs-active .gtabs-check" "css_element" should exist
    And ".gtabs-side .gtabs-check" "css_element" should not exist
    And I click on "Exercises" "link" in the ".gsubtabs" "css_element"
    And I toggle the manual completion state of "Exercise page"
    And I reload the page
    And ".gtabs-side .gtabs-side-active .gtabs-check" "css_element" should exist

  Scenario: The course home offers to resume where I left off
    Given I am on the "Course 1" "Course" page logged in as "student1"
    And ".gresume" "css_element" should not exist
    And I click on "Section 2" "link" in the ".gtabs" "css_element"
    When I am on "Course 1" course homepage
    Then I should see "Section 2" in the ".gresume" "css_element"
    And I click on ".gresume-link" "css_element"
    And I should see "Section 2" in the ".gtabs .gtabs-active" "css_element"

  Scenario: Custom tabs are set in the course settings, and editors are told about sections without a tab
    Given I am on the "Course 1" "Course editing" page logged in as "teacher1"
    And I set the following fields to these values:
      | Tabs        | Custom tabs |
      | Tab 2: name | Course area |
      | Tab 3: name |             |
    When I press "Save and display"
    Then "Course area" "link" should exist in the ".gtabs" "css_element"
    And "Section 2" "link" should not exist in the ".gtabs" "css_element"
    And "Section 2" "link" should exist in the ".gtabs-side" "css_element"
    And I turn editing mode on
    And I should see "No tab opens these sections" in the ".gtabs-orphans" "css_element"
    And I should see "Section 2" in the ".gtabs-orphans" "css_element"
