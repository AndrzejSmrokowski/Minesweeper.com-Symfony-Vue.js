Feature: User creation API

  In order to register in the system
  As a client app
  I need to be able to create a new user via the API

  Scenario: Creating a new user via the API
    Given I have the payload:
        """
        {
            "username": "testuser",
            "password": "testPassword123",
            "email": "testuser@example.com"
        }
        """
    When I request "/users" with POST
    Then the response status code should be 201
    And the response should contain json:
        """
        {
            "message": "User created successfully"
        }
        """

  Scenario: Attempting to create a user with an existing email
    Given I have the payload:
        """
        {
            "username": "testuser2",
            "password": "testpassword2",
            "email": "testuser@example.com"
        }
        """
    When I request "/users" with POST
    Then the response status code should be 400
    And the response should contain json:
        """
        {
            "error": "User with email testuser@example.com already exists!"
        }
        """