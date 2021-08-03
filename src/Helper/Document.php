<?php


namespace Sxqibo\FastSpapi\Helper;

use Sxqibo\FastSpapi\ASECryptoStream;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Document
{
    /**
     * @param $payload : Response from createFeedDocument Function.
     * @param $contentType : Content type used during createFeedDocument function call.
     * @param $feedContentFilePath : Path to file that contain data to be uploaded.
     * @return string
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadFeedDocument($payload, $contentType, $feedContent)
    {
        $feedUploadUrl = $payload['url'];
        // utf8 !
        // $file = utf8_encode($file);

        // my http client
        $client = new Client(['exceptions' => false]);

        $request = new Request(
        // PUT!
            'PUT',
            // predefined url
            $feedUploadUrl,
            // content type equal to content type from response createFeedDocument-operation
            array('Content-Type' => $contentType),
            // resource File
            $feedContent
        );

        $response       = $client->send($request);
        $HTTPStatusCode = $response->getStatusCode();

        if ($HTTPStatusCode == 200) {
            return 'Done';
        } else {
            return $response->getBody()->getContents();
        }
    }

    /**
     * @param $payload : Response from getFeedDocument Function. e.g.: response['payload']
     * @return array : Feed Processing Report.
     */
    public function downloadFeedProcessingReport($payload, $type = 'xml')
    {
        $feedDownloadUrl = $payload['url'];
        $downData        = file_get_contents($feedDownloadUrl);

        if (isset($payload['compressionAlgorithm']) && $payload['compressionAlgorithm'] == 'GZIP') {
            $downData = gzdecode($downData);
        }
        
        if ($type == 'xml') {
            $downData = preg_replace('/\s+/S', " ", $downData);
            $xml      = simplexml_load_string($downData);
            $json     = json_encode($xml);
            $downData = json_decode($json, true);
        }

        return $downData;
    }

    /**
     * @param $payload : Response from createFeedDocument Function. e.g.: response['payload']
     * @param $contentType : Content type used during createFeedDocument function call.
     * @param $feedContentFilePath : Path to file that contain data to be uploaded.
     * @return string
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadFeedDocumentV1($payload, $contentType, $feedContentFilePath)
    {
        $encryptionDetails = $payload['encryptionDetails'];
        $feedUploadUrl     = $payload['url'];

        $key                  = $encryptionDetails['key'];
        $initializationVector = $encryptionDetails['initializationVector'];

        // base64 decode before using in encryption
        $initializationVector = base64_decode($initializationVector, true);
        $key                  = base64_decode($key, true);

        // get file to upload
        $fileResourceType = gettype($feedContentFilePath);

        // resource or string ? make it to a string
        if ($fileResourceType == 'resource') {
            $file = stream_get_contents($feedContentFilePath);
        } else {
            $file = file_get_contents($feedContentFilePath);
        }

        // utf8 !
        $file = utf8_encode($file);

        // encrypt string and get value as base64 encoded string
        $encryptedFile = ASECryptoStream::encrypt($file, $key, $initializationVector);

        // my http client
        $client = new Client(['exceptions' => false]);

        $request = new Request(
        // PUT!
            'PUT',
            // predefined url
            $feedUploadUrl,
            // content type equal to content type from response createFeedDocument-operation
            array('Content-Type' => $contentType),
            // resource File
            $encryptedFile
        );

        $response       = $client->send($request);
        $HTTPStatusCode = $response->getStatusCode();

        if ($HTTPStatusCode == 200) {
            return 'Done';
        } else {
            return $response->getBody()->getContents();
        }
    }

    /**
     * @param $payload : Response from getFeedDocument Function. e.g.: response['payload']
     * @return array : Feed Processing Report.
     */
    public function downloadFeedProcessingReportV1($payload)
    {
        $encryptionDetails = $payload['encryptionDetails'];
        $feedDownloadUrl   = $payload['url'];

        $key                  = $encryptionDetails['key'];
        $initializationVector = $encryptionDetails['initializationVector'];

        // base64 decode before using in encryption
        $initializationVector = base64_decode($initializationVector, true);
        $key                  = base64_decode($key, true);

        $decryptedFile = ASECryptoStream::decrypt(file_get_contents($feedDownloadUrl), $key, $initializationVector);
        if (isset($payload['compressionAlgorithm']) && $payload['compressionAlgorithm'] == 'GZIP') {
            $decryptedFile = gzdecode($decryptedFile);
        }
        $decryptedFile = preg_replace('/\s+/S', " ", $decryptedFile);

        $xml  = simplexml_load_string($decryptedFile);
        $json = json_encode($xml);
        return json_decode($json, true);
    }
}
