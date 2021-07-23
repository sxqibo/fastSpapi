<?php
/**
* This class is autogenerated by the Spapi class generator
* Date of generation: 2020-12-22
* Specification: ttps://github.com/amzn/selling-partner-api-models/blob/main/models/finances-api-model/financesV0.json
* Source MD5 signature: 0619e4991bc858b7843495617005d771
*
*
* Selling Partner API for Finances
* The Selling Partner API for Finances helps you obtain financial information relevant to a seller's business. You can obtain financial events for a given order, financial event group, or date range without having to wait until a statement period closes. You can also obtain financial event groups for a given date range.
*/
namespace Sxqibo\FastSpapi\Api;
use Sxqibo\FastSpapi\Client;

class Finances extends Client {

  /**
  * Operation listFinancialEventGroups
  *
  * @param array $queryParams
  *    - *maxResultsPerPage* integer - The maximum number of results to return per page.
  *    - *financialEventGroupStartedBefore* string - A date used for selecting financial event groups that opened before (but not at) a specified date and time, in ISO 8601 format. The date-time  must be later than FinancialEventGroupStartedAfter and no later than two minutes before the request was submitted. If FinancialEventGroupStartedAfter and FinancialEventGroupStartedBefore are more than 180 days apart, no financial event groups are returned.
  *    - *financialEventGroupStartedAfter* string - A date used for selecting financial event groups that opened after (or at) a specified date and time, in ISO 8601 format. The date-time must be no later than two minutes before the request was submitted.
  *    - *nextToken* string - A string token returned in the response of your previous request.
  *
  */
  public function listFinancialEventGroups($queryParams = [])
  {
    return $this->send("/finances/v0/financialEventGroups", [
      'method' => 'GET',
      'query' => $queryParams,
    ]);
  }

  /**
  * Operation listFinancialEventsByGroupId
  *
  * @param string $eventGroupId The identifier of the financial event group to which the events belong.
  *
  * @param array $queryParams
  *    - *maxResultsPerPage* integer - The maximum number of results to return per page.
  *    - *nextToken* string - A string token returned in the response of your previous request.
  *
  */
  public function listFinancialEventsByGroupId($eventGroupId, $queryParams = [])
  {
    return $this->send("/finances/v0/financialEventGroups/{$eventGroupId}/financialEvents", [
      'method' => 'GET',
      'query' => $queryParams,
    ]);
  }

  /**
  * Operation listFinancialEventsByOrderId
  *
  * @param string $orderId An Amazon-defined order identifier, in 3-7-7 format.
  *
  * @param array $queryParams
  *    - *maxResultsPerPage* integer - The maximum number of results to return per page.
  *    - *nextToken* string - A string token returned in the response of your previous request.
  *
  */
  public function listFinancialEventsByOrderId($orderId, $queryParams = [])
  {
    return $this->send("/finances/v0/orders/{$orderId}/financialEvents", [
      'method' => 'GET',
      'query' => $queryParams,
    ]);
  }

  /**
  * Operation listFinancialEvents
  *
  * @param array $queryParams
  *    - *maxResultsPerPage* integer - The maximum number of results to return per page.
  *    - *postedAfter* string - A date used for selecting financial events posted after (or at) a specified time. The date-time must be no later than two minutes before the request was submitted, in ISO 8601 date time format.
  *    - *postedBefore* string - A date used for selecting financial events posted before (but not at) a specified time. The date-time must be later than PostedAfter and no later than two minutes before the request was submitted, in ISO 8601 date time format. If PostedAfter and PostedBefore are more than 180 days apart, no financial events are returned. You must specify the PostedAfter parameter if you specify the PostedBefore parameter. Default: Now minus two minutes.
  *    - *nextToken* string - A string token returned in the response of your previous request.
  *
  */
  public function listFinancialEvents($queryParams = [])
  {
    return $this->send("/finances/v0/financialEvents", [
      'method' => 'GET',
      'query' => $queryParams,
    ]);
  }
}