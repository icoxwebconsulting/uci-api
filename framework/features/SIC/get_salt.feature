Feature: Get customer salt
  In order to get customer salt
  As an none authenticated customer
  I need to be able to collect customer salt by his email

  Scenario: Get customer salt
    Given I have the following data on collection Customer
    """
    [
        {
            "_id": "575b7c0b0419c906e262d54b",
            "username": "yasmanycm@gmail.com",
            "usernameCanonical": "yasmanycm@gmail.com",
            "email": "yasmanycm@gmail.com",
            "emailCanonical": "yasmanycm@gmail.com",
            "salt": "a0s8d0a9s8d0as8d0as8d0as8d09as8d0asd"
        }
    ]
    """
    When I make a GET request to "api/v2/customers/yasmanycm@gmail.com/salt"
    Then I get a successful response
    And I validate is JSON response
    And I validate the response is
    """
    {
        "salt": "a0s8d0a9s8d0as8d0as8d0as8d09as8d0asd"
    }
    """
    And I validate only the following data exists on collection Customer
    """
    [
        {
            "_id": "575b7c0b0419c906e262d54b",
            "username": "yasmanycm@gmail.com",
            "usernameCanonical": "yasmanycm@gmail.com",
            "email": "yasmanycm@gmail.com",
            "emailCanonical": "yasmanycm@gmail.com",
            "salt": "a0s8d0a9s8d0as8d0as8d0as8d09as8d0asd"
        }
    ]
    """

  Scenario: Get error since customer does not exist
    Given I have the following data on collection Customer
    """
    [
        {
            "_id": "575b7c0b0419c906e262d54b",
            "username": "yasmanycm@gmail.com",
            "usernameCanonical": "yasmanycm@gmail.com",
            "email": "yasmanycm@gmail.com",
            "emailCanonical": "yasmanycm@gmail.com",
            "salt": "a0s8d0a9s8d0as8d0as8d0as8d09as8d0asd"
        }
    ]
    """
    When I make a GET request to "api/v2/customers/yasmany@gmail.com/salt"
    Then I get an API error response
    And I validate is JSON response
    And I validate the response is
    """
    {
        "code": 500,
        "message": "Customer not found."
    }
    """
    And I validate only the following data exists on collection Customer
    """
    [
        {
            "_id": "575b7c0b0419c906e262d54b",
            "username": "yasmanycm@gmail.com",
            "usernameCanonical": "yasmanycm@gmail.com",
            "email": "yasmanycm@gmail.com",
            "emailCanonical": "yasmanycm@gmail.com",
            "salt": "a0s8d0a9s8d0as8d0as8d0as8d09as8d0asd"
        }
    ]
    """