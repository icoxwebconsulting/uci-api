Feature: Get sics
  In order to all the sics
  As an none authenticated customer
  I need to be able to collect all sics

  Scenario: Get sics
    Given I have the following data on collection SIC
    """
    [
        {
            "_id": "575b7c0b0419c906e262d54b",
            "code": "123",
            "office": "office1",
            "title": "title1"
        },
        {
            "_id": "575b7c0b0419c906e262d54c",
            "code": "456",
            "office": "office2",
            "title": "title2"
        }
    ]
    """
    When I make a GET request to "api/v1/sics"
    Then I get a successful response
    And I validate is JSON response
    And I validate the response is
    """
    [
        {
            "id": "575b7c0b0419c906e262d54b",
            "code": "123",
            "office": "office1",
            "title": "title1",
            "_links": {
                "list_sics": {"href":"\/app_dev.php\/api\/v1\/sics"}
            }
        },
        {
            "id": "575b7c0b0419c906e262d54c",
            "code": "456",
            "office": "office2",
            "title": "title2",
            "_links": {
                "list_sics": {"href":"\/app_dev.php\/api\/v1\/sics"}
            }
        }
    ]
    """

  Scenario: Get none sics
    Given I make a GET request to "api/v1/sics"
    Then I get a successful response
    And I validate is JSON response
    And I validate the response is
    """
    []
    """