<?php
/**
* This class is autogenerated by the Spapi class generator
* Date of generation: 2021-07-03
* Specification: ttps://github.com/amzn/selling-partner-api-models/blob/main/models/aplus-content-api-model/aplusContent_2020-11-01.json
* Source MD5 signature: 2a652f17aeb6125578131c332324ba06
*
*
* Selling Partner API for A+ Content Management
* With the A+ Content API, you can build applications that help selling partners add rich marketing content to their Amazon product detail pages. A+ content helps selling partners share their brand and product story, which helps buyers make informed purchasing decisions. Selling partners assemble content by choosing from content modules and adding images and text.
*/
namespace Sxqibo\FastSpapi\Api;
use Sxqibo\FastSpapi\Client;

class AplusContent extends Client {

  /**
  * Operation searchContentDocuments
  *
  * @param array $queryParams
  *    - *marketplaceId* string - The identifier for the marketplace where the A+ Content is published.
  *    - *pageToken* string - A page token from the nextPageToken response element returned by your previous call to this operation. nextPageToken is returned when the results of a call exceed the page size. To get the next page of results, call the operation and include pageToken as the only parameter. Specifying pageToken with any other parameter will cause the request to fail. When no nextPageToken value is returned there are no more pages to return. A pageToken value is not usable across different operations.
  *
  */
  public function searchContentDocuments($queryParams = [])
  {
    return $this->send("/aplus/2020-11-01/contentDocuments", [
      'method' => 'GET',
      'query' => $queryParams,
    ]);
  }

  /**
  * Operation createContentDocument
  *
  * @param array $queryParams
  *    - *marketplaceId* string - The identifier for the marketplace where the A+ Content is published.
  *
  */
  public function createContentDocument($queryParams = [], $body = [])
  {
    return $this->send("/aplus/2020-11-01/contentDocuments", [
      'method' => 'POST',
      'query' => $queryParams,
      'json' => $body
    ]);
  }

  /**
  * Operation getContentDocument
  *
  * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ Content identifier.
  *
  * @param array $queryParams
  *    - *marketplaceId* string - The identifier for the marketplace where the A+ Content is published.
  *    - *includedDataSet* array - The set of A+ Content data types to include in the response.
  *
  */
  public function getContentDocument($contentReferenceKey, $queryParams = [])
  {
    return $this->send("/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}", [
      'method' => 'GET',
      'query' => $queryParams,
    ]);
  }

  /**
  * Operation updateContentDocument
  *
  * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ Content identifier.
  *
  * @param array $queryParams
  *    - *marketplaceId* string - The identifier for the marketplace where the A+ Content is published.
  *
  */
  public function updateContentDocument($contentReferenceKey, $queryParams = [], $body = [])
  {
    return $this->send("/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}", [
      'method' => 'POST',
      'query' => $queryParams,
      'json' => $body
    ]);
  }

  /**
  * Operation listContentDocumentAsinRelations
  *
  * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ Content identifier.
  *
  * @param array $queryParams
  *    - *marketplaceId* string - The identifier for the marketplace where the A+ Content is published.
  *    - *includedDataSet* array - The set of A+ Content data types to include in the response. If you do not include this parameter, the operation returns the related ASINs without metadata.
  *    - *asinSet* array - The set of ASINs.
  *    - *pageToken* string - A page token from the nextPageToken response element returned by your previous call to this operation. nextPageToken is returned when the results of a call exceed the page size. To get the next page of results, call the operation and include pageToken as the only parameter. Specifying pageToken with any other parameter will cause the request to fail. When no nextPageToken value is returned there are no more pages to return. A pageToken value is not usable across different operations.
  *
  */
  public function listContentDocumentAsinRelations($contentReferenceKey, $queryParams = [])
  {
    return $this->send("/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}/asins", [
      'method' => 'GET',
      'query' => $queryParams,
    ]);
  }

  /**
  * Operation postContentDocumentAsinRelations
  *
  * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ content identifier.
  *
  * @param array $queryParams
  *    - *marketplaceId* string - The identifier for the marketplace where the A+ Content is published.
  *
  */
  public function postContentDocumentAsinRelations($contentReferenceKey, $queryParams = [], $body = [])
  {
    return $this->send("/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}/asins", [
      'method' => 'POST',
      'query' => $queryParams,
      'json' => $body
    ]);
  }

  /**
  * Operation validateContentDocumentAsinRelations
  *
  * @param array $queryParams
  *    - *marketplaceId* string - The identifier for the marketplace where the A+ Content is published.
  *    - *asinSet* array - The set of ASINs.
  *
  */
  public function validateContentDocumentAsinRelations($queryParams = [], $body = [])
  {
    return $this->send("/aplus/2020-11-01/contentAsinValidations", [
      'method' => 'POST',
      'query' => $queryParams,
      'json' => $body
    ]);
  }

  /**
  * Operation searchContentPublishRecords
  *
  * @param array $queryParams
  *    - *marketplaceId* string - The identifier for the marketplace where the A+ Content is published.
  *    - *asin* string - The Amazon Standard Identification Number (ASIN).
  *    - *pageToken* string - A page token from the nextPageToken response element returned by your previous call to this operation. nextPageToken is returned when the results of a call exceed the page size. To get the next page of results, call the operation and include pageToken as the only parameter. Specifying pageToken with any other parameter will cause the request to fail. When no nextPageToken value is returned there are no more pages to return. A pageToken value is not usable across different operations.
  *
  */
  public function searchContentPublishRecords($queryParams = [])
  {
    return $this->send("/aplus/2020-11-01/contentPublishRecords", [
      'method' => 'GET',
      'query' => $queryParams,
    ]);
  }

  /**
  * Operation postContentDocumentApprovalSubmission
  *
  * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ content identifier.
  *
  * @param array $queryParams
  *    - *marketplaceId* string - The identifier for the marketplace where the A+ Content is published.
  *
  */
  public function postContentDocumentApprovalSubmission($contentReferenceKey, $queryParams = [])
  {
    return $this->send("/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}/approvalSubmissions", [
      'method' => 'POST',
      'query' => $queryParams,
    ]);
  }

  /**
  * Operation postContentDocumentSuspendSubmission
  *
  * @param string $contentReferenceKey The unique reference key for the A+ Content document. A content reference key cannot form a permalink and may change in the future. A content reference key is not guaranteed to match any A+ content identifier.
  *
  * @param array $queryParams
  *    - *marketplaceId* string - The identifier for the marketplace where the A+ Content is published.
  *
  */
  public function postContentDocumentSuspendSubmission($contentReferenceKey, $queryParams = [])
  {
    return $this->send("/aplus/2020-11-01/contentDocuments/{$contentReferenceKey}/suspendSubmissions", [
      'method' => 'POST',
      'query' => $queryParams,
    ]);
  }
}