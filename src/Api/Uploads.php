<?php
/**
* This class is autogenerated by the Spapi class generator
* Date of generation: 2020-12-22
* Specification: ttps://github.com/amzn/selling-partner-api-models/blob/main/models/uploads-api-model/uploads_2020-11-01.json
* Source MD5 signature: 20f4a707fa1bd67af44c4a2c4c6fdf51
*
*
* Selling Partner API for Uploads
* The Selling Partner API for Uploads provides operations that support uploading files.
*/
namespace Sxqibo\FastSpapi\Api;
use Sxqibo\FastSpapi\Client;

class Uploads extends Client {

  /**
  * Operation createUploadDestinationForResource
  *
  * @param string $resource The URL of the resource for the upload destination that you are creating. For example, to create an upload destination for a Buyer-Seller Messaging message, the {resource} would be /messaging and the path would be  /uploads/v1/uploadDestinations/messaging
  *
  * @param array $queryParams
  *    - *marketplaceIds* array - A list of marketplace identifiers. This specifies the marketplaces where the upload will be available. Only one marketplace can be specified.
  *    - *contentMD5* string - An MD5 hash of the content to be submitted to the upload destination. This value is used to determine if the data has been corrupted or tampered with during transit.
  *    - *contentType* string - The content type of the file to be uploaded.
  *
  */
  public function createUploadDestinationForResource($resource, $queryParams = [])
  {
    return $this->send("/uploads/2020-11-01/uploadDestinations/{$resource}", [
      'method' => 'POST',
      'query' => $queryParams,
    ]);
  }
}
