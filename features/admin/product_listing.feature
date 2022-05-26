@managing_product_listings
Feature: Listing product listings
  In order to verify product listing
  As an Administrator
  I need to be able to see product listings list

  Background:
    Given there is an admin user "admin" with password "admin"
    And I am logged in as an admin

  @ui
  Scenario: Listing product listings
    Given there are 3 product listings
    And I am on "/admin"
    When I follow "Product listings"
    Then I should see 3 product listings

  @ui
  Scenario: Listing product details
    Given there is 1 product listing
    And I am on "/admin"
    And I follow "Product listings"
    And I should see 1 product listing
    When I follow "Details"
    Then I should see url "#\/admin\/product-listings\/(\d+)#"
