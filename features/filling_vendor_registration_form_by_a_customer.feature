@vendor_register
Feature: Filling vendor registration form by a customer
  In order to create new vendor account
  As a customer
  I can fill registration form

  Background:
    Given the store operates on a single channel in "United States"
    And I am a logged in customer

  Scenario: Attempting to submit empty form
    When I am on "/en_US/vendor/register"
    And I press "Become a Vendor"
    Then I should see "sylius-validation-error" "6" times
    
  Scenario: Filling form with data that fails validation
    When I am on "/en_US/vendor/register"
    And I fill in "vendor_companyName" with "te"
    And I fill in "vendor_taxIdentifier" with "56"
    And I fill in "vendor_phoneNumber" with "55"   
    And I fill in "vendor_vendorAddress_city" with "an"
    And I fill in "vendor_vendorAddress_street" with "et"
    And I fill in "vendor_vendorAddress_postalCode" with "de"
    And I press "Become a Vendor"
    Then I should see "sylius-validation-error" "6" times

  Scenario: Correct completion of the form
    When I am on "/en_US/vendor/register"
    And I fill in "vendor_companyName" with "testCompanyName"
    And I fill in "vendor_taxIdentifier" with "6546546456"
    And I fill in "vendor_phoneNumber" with "555555555"   
    And I fill in "vendor_vendorAddress_city" with "Milan"
    And I fill in "vendor_vendorAddress_street" with "test_street"
    And I fill in "vendor_vendorAddress_postalCode" with "test_postalCode"
    And I press "Become a Vendor"    
    Then I should see "Thank you for filling the Vendor registration form. Your request now will be reviewed by our administrators"
