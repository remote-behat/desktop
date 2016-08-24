Feature: Having a working reservation-surveys endpoint

  Background:
    Given a survey resource validate against JSON schema:
        """
        {
            "type": "object",
            "properties": {
                "id_survey": {
                    "type": "integer",
                    "required": true
                },
                "id_customer": {
                    "type": "integer",
                    "required": true
                },
                "id_reservation": {
                    "type": "integer",
                    "required": true
                },
                "booking_done": {
                    "type": "boolean",
                    "required": true
                },
                "reason_no_done": {
                    "type": ["string", "null"],
                    "required": false
                },
                "booking_transmitted": {
                    "type": ["boolean", "null"],
                    "required": false
                },
                "promo_done": {
                    "type": ["string", "null"],
                    "required": false
                },
                "promo_good": {
                    "type": ["string", "null"],
                    "required": false
                },
                "promo_good_other": {
                    "type": ["string", "null"],
                    "required": false
                },
                "promo_good_other": {
                    "type": ["string", "null"],
                    "required": false
                },
                "yums_fid_done": {
                    "type": ["string", "null"],
                    "required": false
                },
                "restaurant_expect": {
                    "type": ["boolean", "null"],
                    "required": false
                },
                "reason_no_expect": {
                    "type": ["array"],
                    "required": false
                },
                "reason_no_expect_other": {
                    "type": ["string", "null"],
                    "required": false
                },
                "suggestion": {
                    "type": ["string", "null"],
                    "required": false
                }
            }
        }
        """
    And there are the following surveys:
      | id_survey | id_customer | id_reservation | booking_done | booking_transmitted | restaurant_expect |
      | 1         | 456         | 789            | true         | true                | true              |
      | 2         | 1           | 2              | true         | true                | true              |
      | 3         | 2           | 3              | false        |                     |                   |

  # GET /api/reservation-surveys/{id}
  Scenario: Successfully fetching a single survey
    When I send a GET request to "/api/reservation-surveys/1"
    Then I should get 200 response containing a survey resource

  Scenario: Failing to fetch a non-existing survey
    When I send a GET request to "/api/reservation-surveys/42"
    Then I should get 404 response

  # GET /api/reservation-surveys
  Scenario: Successfully fetching list of surveys
    When I send a GET request to "/api/reservation-surveys"
    Then I should get 200 response containing an array of 3 "survey" resources

  Scenario: Successfully fetching list of surveys for a given customer
    When I send a GET request to "/api/reservation-surveys?id_customer=1"
    Then I should get 200 response containing an array of 1 "survey" resources

  Scenario: Successfully fetching list of surveys for a given reservation
    When I send a GET request to "/api/reservation-surveys?id_reservation=2"
    Then I should get 200 response containing an array of 1 "survey" resources

  Scenario: Successfully fetching list of surveys for a given reservation and a given customer
    When I send a GET request to "/api/reservation-surveys?id_reservation=2&id_customer=1"
    Then I should get 200 response containing an array of 1 "survey" resources

  Scenario: Successfully fetching list of surveys for a given reservation and a non corresponding given customer
    When I send a GET request to "/api/reservation-surveys?id_reservation=1&id_customer=22"
    Then I should get 200 response containing an array of 0 "survey" resources

  Scenario: Successfully fetching list of surveys with a wrong parameter (ignoring it)
    When I send a GET request to "/api/reservation-surveys?id_plop=1"
    Then I should get 200 response containing an array of 3 "survey" resources

  # POST /api/reservation-surveys

  Scenario: Successfully creating a simple reservation survey
    When I send a GET request to "/api/reservation-surveys/4"
    Then I should get 404 response
    When I send a POST request to "/api/reservation-surveys" with JSON body:
    """
    {
        "id_customer": 12,
        "id_reservation": 123,
        "booking_done": true,
        "booking_transmitted": false,
        "restaurant_expect": true,
        "suggestion": "I love Lafourchette.com"
    }
    """
    Then I should get 201 response with JSON body:
    """
    {
        "id_survey": 4,
        "id_customer": 12,
        "id_reservation": 123,
        "booking_done": true,
        "reason_no_done": "",
        "booking_transmitted": false,
        "promo_done": "",
        "promo_good": "",
        "promo_good_other": "",
        "yums_fid_done": "",
        "restaurant_expect": true,
        "reason_no_expect": [],
        "reason_no_expect_other": "",
        "suggestion": "I love Lafourchette.com"
    }
    """
    When I send a GET request to "/api/reservation-surveys/4"
    Then I should get 200 response with JSON body:
    """
    {
        "id_survey": 4,
        "id_customer": 12,
        "id_reservation": 123,
        "booking_done": true,
        "reason_no_done": "",
        "booking_transmitted": false,
        "promo_done": "",
        "promo_good": "",
        "promo_good_other": "",
        "yums_fid_done": "",
        "restaurant_expect": true,
        "reason_no_expect": [],
        "reason_no_expect_other": "",
        "suggestion": "I love Lafourchette.com"
    }
    """

  Scenario: Failing to create a survey with invalid parameters
    When I send a POST request to "/api/reservation-surveys" with JSON body:
    """
    {
        "id_customer": "12",
        "booking_done": null,
        "booking_transmitted": "true",
        "promo_done": "yes_clamed",
        "promo_good": "yes_but_more",
        "restaurant_expect": false,
        "reason_no_expect": ["photo", "rate", "other", "coucou"],
        "reason_no_expect_other": "Dishes didn't match the pictures and the restaurant was over-rated !",
        "suggestion": "Lafourchette sent me to an awfull restaurant !"
    }
    """
    Then I should get 422 response with JSON body:
    """
    [
        {"property_path":"idReservation","message":"This value should not be null."},
        {"property_path":"bookingDone","message":"This value should not be null."},
        {"property_path":"promoDone","message":"The value you selected is not a valid choice."},
        {"property_path":"promoGood","message":"The value you selected is not a valid choice."},
        {"property_path":"reasonNoExpect","message":"One or more of the given values is invalid."}
    ]
    """

  Scenario: Failing to create a survey with already existing id_customer/id_reservation pair
    When I send a POST request to "/api/reservation-surveys" with JSON body:
    """
    {
        "id_customer": 2,
        "id_reservation": 3,
        "booking_done": false
    }
    """
    Then I should get 409 response with JSON body:
    """
    {
        "message":"Could not create resource : duplicate entry"
    }
    """

  # PUT /api/reservation-surveys/{id}

  Scenario: Successfully updating a survey
    When I send a PUT request to "/api/reservation-surveys/1" with JSON body:
    """
    {
        "id_survey": 1,
        "id_customer": 456,
        "id_reservation": 789,
        "booking_done": true,
        "booking_transmitted": false,
        "suggestion": "I've changed my answers !"
    }
    """
    Then I should get 200 response with JSON body:
    """
    {
        "id_survey": 1,
        "id_customer": 456,
        "id_reservation": 789,
        "booking_done": true,
        "reason_no_done": "",
        "booking_transmitted": false,
        "promo_done": "",
        "promo_good": "",
        "promo_good_other": "",
        "yums_fid_done": "",
        "restaurant_expect": null,
        "reason_no_expect": [],
        "reason_no_expect_other": "",
        "suggestion": "I've changed my answers !"
    }
    """

  Scenario: Failing to update a survey with invalid parameters
    When I send a PUT request to "/api/reservation-surveys/1" with JSON body:
    """
    {
        "id_survey": 1,
        "id_customer": "12",
        "booking_done": null,
        "booking_transmitted": "true",
        "promo_done": "yes_clamed",
        "promo_good": "yes_but_more",
        "restaurant_expect": false,
        "reason_no_expect": ["photo", "rate", "other", "coucou"],
        "reason_no_expect_other": "Dishes didn't match the pictures and the restaurant was over-rated !",
        "suggestion": "Lafourchette sent me to an awfull restaurant !"
    }
    """
    Then I should get 422 response with JSON body:
    """
    [
        {"property_path":"idReservation","message":"This value should not be null."},
        {"property_path":"bookingDone","message":"This value should not be null."},
        {"property_path":"promoDone","message":"The value you selected is not a valid choice."},
        {"property_path":"promoGood","message":"The value you selected is not a valid choice."},
        {"property_path":"reasonNoExpect","message":"One or more of the given values is invalid."}
    ]
    """

  Scenario: Failing to update a survey to already existing id_customer/id_reservation pair
    When I send a PUT request to "/api/reservation-surveys/1" with JSON body:
    """
    {
        "id_survey": 1,
        "id_customer": 1,
        "id_reservation": 2,
        "booking_done": true
    }
    """
    Then I should get 409 response

  Scenario: Failing to create a survey via this method by trying to update the survey's id
    When I send a PUT request to "/api/reservation-surveys/1" with JSON body:
    """
    {
        "id_survey": 12,
        "id_customer": 456,
        "id_reservation": 789,
        "booking_done": true
    }
    """
    Then I should get 402 response

  Scenario: Failing to update a non-existing survey
    When I send a PUT request to "/api/reservation-surveys/42" with JSON body:
    """
    {
        "id_survey": 42,
        "id_customer": 678,
        "id_reservation": 91011,
        "booking_done": true
    }
    """
    Then I should get 404 response
