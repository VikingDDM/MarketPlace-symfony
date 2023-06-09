@managing_product_listings
Feature: Verifying product listing
  In order to create new product
  As an Administrator
  I need to be able to verify product listing

  Background:
    Given there is an admin user "admin" with password "admin"
    And I am logged in as an admin

  @ui
  Scenario: Accept product listing
    Given there is 1 product listing
    And I am on "/admin"
    And I follow "Product listings"
    And I should see 1 product listing
    And I should see product's listing status "under_verification"
    And I follow "Details"
    And I should see url "#\/admin\/product-listings\/(\d+)#"
    And I click "Accept" button
    Then I should see url "#\/admin\/product-listings\/$#"
    And I should see product's listing status "verified"
