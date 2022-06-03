@verifying_vendors
Feature: Verifying vendors
  In order to verify a vendor
  As an Administrator
  I should be able to see vendor show page

  @ui
  Scenario: Verifying vendors
    Given there is an admin user "admin" with password "admin"
    And I am logged in as an admin
    And I am on "/admin"
    And There is an unverified vendor
    When I follow "Vendors"
    And I follow "Details"
    And I click "Verify"
    Then I should see "Vendor has been successfully verified"