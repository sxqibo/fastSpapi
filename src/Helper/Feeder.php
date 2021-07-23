<?php

namespace Sxqibo\FastSpapi\Helper;

use League\Csv\Writer;
use SplTempFileObject;
use Exception;
use Sxqibo\FastSpapi\Api\Feeds;
use Sxqibo\FastSpapi\ContentType;
use Sxqibo\FastSpapi\FeedType;
use think\facade\Log;

class FeedService
{
    const SIGNATURE_VERSION = '2';
    const DATE_FORMAT       = "Y-m-d\TH:i:s.\\0\\0\\0\\Z";
    public $configInfo;
    public $client;
    public $marketplaceIds = [];
    public $contentType    = ContentType::XML;

    public function __construct($cred, $configInfo)
    {
        $this->client = new Feeds($cred, $configInfo);

        $marketplaceIds = $configInfo['marketplaceIds'];
        if (!is_array($marketplaceIds)) {
            $marketplaceIds = [$marketplaceIds];
        }

        $this->marketplaceIds = $marketplaceIds;
    }

    /**
     * Post to create or update a product (_POST_FLAT_FILE_LISTINGS_DATA_)
     * @param object|array $MWSProduct or array of Custom objects
     * @param string $template
     * @param null $version
     * @param null $signature
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function postProduct($MWSProduct, $template = 'Custom', $version = null, $signature = null)
    {
        if (!is_array($MWSProduct)) {
            $MWSProduct = [$MWSProduct];
        }

        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->setDelimiter("\t");

        $csv->insertOne(['TemplateType=' . $template, 'Version=' . $version, 'TemplateSignature=' . $signature]);

        $header = array_keys(end($MWSProduct)->toArray());

        $csv->insertOne($header);
        $csv->insertOne($header);

        foreach ($MWSProduct as $product) {
            $csv->insertOne(array_values($product->toArray()));
        }

        $feedeType         = FeedType::POST_FLAT_FILE_LISTINGS_DATA['name'];
        $this->contentType = $feedeType['contentType'];

        return $this->submitFeed($feedeType, $csv);
    }

    /**
     * Post to create or update a product (_POST_FLAT_FILE_LISTINGS_DATA_)
     * @param array $MWSProduct
     * @param string $template
     * @param null $version
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function postFollowProduct($MWSProduct, $template = 'Offer', $version = null)
    {
        if (!is_array($MWSProduct)) {
            $MWSProduct = [$MWSProduct];
        }
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->setDelimiter("\t");

        $csv->insertOne(['TemplateType=' . $template, 'Version=' . $version]);

        $header = array_keys($MWSProduct[0]);
        $csv->insertOne($header);
        $csv->insertOne($header);

        foreach ($MWSProduct as $product) {
            $csv->insertOne(array_values($product));
        }

        $feedeType         = FeedType::POST_FLAT_FILE_LISTINGS_DATA['name'];
        $this->contentType = $feedeType['contentType'];

        return $this->submitFeed($feedeType, $csv);
    }

    /**
     * Update a product's Relationship
     *
     * @param array $array array containing arrays with next keys: [parent_sku, relation_list]
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateRelationship(array $array)
    {
        $feed = [
            'MessageType' => 'Relationship',
            'Message'     => []
        ];
        foreach ($array as $item) {
            $relationList = $item['relation_list'];
            $newData      = [
                'MessageID'     => rand(),
                'OperationType' => 'Update',
                'Relationship'  => [
                    'ParentSKU' => $item['parent_sku'],
                    'Relation'  => [],
                ]
            ];

            foreach ($relationList as $relation) {
                $newData['Relationship']['Relation'][] = [
                    'SKU'  => $relation['sku'],
                    'Type' => $relation['type'] ?? 'Variation',
                ];
            }

            $feed['Message'][] = $newData;
        }

        return $this->SubmitFeed(FeedType::POST_PRODUCT_RELATIONSHIP_DATA['name'], $feed);
    }

    /**
     * Update a product's price
     * @param array $standardprice an array containing sku as key and price as value
     * @param array|null $saleprice
     * @return array feed submission result
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function updatePrice(array $standardprice, array $saleprice = null)
    {
        $feed = [
            'MessageType' => 'Price',
            'Message'     => []
        ];
        foreach ($standardprice as $sku => $price) {
            $feed['Message'][] = [
                'MessageID' => rand(),
                'Price'     => [
                    'SKU'           => $sku,
                    'StandardPrice' => [
                        '_value'      => strval($price),
                        '_attributes' => [
                            'currency' => 'DEFAULT'
                        ]
                    ]
                ]
            ];
            if (isset($saleprice[$sku]) && is_array($saleprice[$sku])) {
                $feed['Message'][count($feed['Message']) - 1]['Price']['Sale'] = [
                    'StartDate' => $saleprice[$sku]['StartDate']->format(self::DATE_FORMAT),
                    'EndDate'   => $saleprice[$sku]['EndDate']->format(self::DATE_FORMAT),
                    'SalePrice' => [
                        '_value'      => strval($saleprice[$sku]['SalePrice']),
                        '_attributes' => [
                            'currency' => 'DEFAULT'
                        ]
                    ]
                ];
            }
        }

        return $this->SubmitFeed(FeedType::POST_PRODUCT_PRICING_DATA['name'], $feed);
    }

    /**
     * Update a product's image
     *
     * @param array $array array containing arrays with next keys: [sku, image_type, image_location]
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateImage(array $array)
    {
        $feed = [
            'MessageType' => 'ProductImage',
            'Message'     => []
        ];
        foreach ($array as $item) {
            $feed['Message'][] = [
                'MessageID'     => rand(),
                'OperationType' => 'Update',
                'ProductImage'  => [
                    'SKU'           => $item['sku'],
                    'ImageType'     => $item['image_type'],
                    'ImageLocation' => $item['image_location']
                ]
            ];
        }

        return $this->SubmitFeed(FeedType::POST_PRODUCT_IMAGE_DATA['name'], $feed);
    }

    /**
     * Update a product's stock quantity
     *
     * @param array $array array containing arrays with next keys: [sku, quantity, latency]
     * @return array feed submission result
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function updateStockWithFulfillmentLatency(array $array)
    {
        $feed = [
            'MessageType' => 'Inventory',
            'Message'     => []
        ];
        foreach ($array as $item) {
            $feed['Message'][] = [
                'MessageID'     => rand(),
                'OperationType' => 'Update',
                'Inventory'     => [
                    'SKU'                => $item['sku'],
                    'Quantity'           => (int)$item['quantity'],
                    'FulfillmentLatency' => $item['latency']
                ]
            ];
        }

        return $this->SubmitFeed(FeedType::POST_INVENTORY_AVAILABILITY_DATA['name'], $feed);
    }

    /**
     * Update a product's stock quantity
     * @param array $array array containing sku as key and quantity as value
     * @return array feed submission result
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function updateStock(array $array)
    {
        $feed = [
            'MessageType' => 'Inventory',
            'Message'     => []
        ];
        foreach ($array as $sku => $quantity) {
            $feed['Message'][] = [
                'MessageID'     => rand(),
                'OperationType' => 'Update',
                'Inventory'     => [
                    'SKU'      => $sku,
                    'Quantity' => (int)$quantity
                ]
            ];
        }

        return $this->SubmitFeed(FeedType::POST_INVENTORY_AVAILABILITY_DATA['name'], $feed);
    }

    /**
     * Delete product's based on SKU
     * @param array $array array containing sku's
     * @return array feed submission result
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteProductBySKU(array $array)
    {
        $feed = [
            'MessageType' => 'Product',
            'Message'     => []
        ];
        foreach ($array as $sku) {
            $feed['Message'][] = [
                'MessageID'     => rand(),
                'OperationType' => 'Delete',
                'Product'       => [
                    'SKU' => $sku
                ]
            ];
        }

        return $this->SubmitFeed(FeedType::POST_PRODUCT_DATA['name'], $feed);
    }


    /**
     * 提交数据到亚马逊
     *
     * @param $feed
     * @param $feedType
     * @param $contentType
     * @return false|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function submitFeed($feedType, $feedContent)
    {
        $feedDocument = $this->client->createFeedDocument(["contentType" => $this->contentType]);

        Log::info('feed-document' . json_encode($feedDocument));

        $feedDocumentId = $feedDocument['feedDocumentId'];
        $result         = (new Document())->uploadFeedDocument($feedDocument, $this->contentType, $feedContent);

        if ($result !== 'Done') {
            return $result;
        }

        // create feed
        $createFeedParams = [
            "feedType"            => $feedType,
            "marketplaceIds"      => $this->marketplaceIds,
            "inputFeedDocumentId" => $feedDocumentId
        ];

        $feed             = $this->client->createFeed($createFeedParams);

        return $feed;
    }
}
