Feature: Having a statistics reservation-surveys endpoint

  Background:
    Given there are the following surveys:
      | id_survey | id_customer | created_at | id_reservation | booking_done | booking_transmitted | restaurant_expect | promo_done      | promo_good   |
      | 1         | 456         | 2016-01-01 | 789            | 1            | 1                   | 0                 | yes             | yes          |
      | 2         | 1           | 2016-01-02 | 2              | 1            | 1                   | 1                 | yes_but_claimed | yes_but_less |
      | 3         | 2           | 2016-01-03 | 3              | 0            | 1                   | 1                 | no              | no           |
      | 4         | 1           | 2016-01-03 | 3              | 1            | 0                   | 1                 | yes             | yes          |

  # GET /api/reservation-surveys/statistics
  Scenario: Successfully fetching survey's global statistics
    When I send a GET request to "/api/reservation-surveys/statistics"
    Then I should get 200 response

  Scenario: Successfully fetching survey's global statistics weekly aggregated
    When I send a GET request to "/api/reservation-surveys/statistics?weekly"
    Then I should get 200 response

  Scenario: Successfully fetching survey's disappointment reasons statistics
    When I send a GET request to "/api/reservation-surveys/statistics/disappointment-reasons"
    Then I should get 200 response

  Scenario: Successfully fetching survey's disappointment reasons statistics weekly aggregated
    When I send a GET request to "/api/reservation-surveys/statistics/disappointment-reasons?weekly"
    Then I should get 200 response

  Scenario: I want to test a multiline stuff
    When I have a multine like this:
    """
    Salut les babtous
    Ha ha ha
    Ça roule ?
    """
    Then I'm happy
