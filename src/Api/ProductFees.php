<?php
/**
* This class is autogenerated by the Spapi class generator
* Date of generation: 2020-12-22
* Specification: ttps://github.com/amzn/selling-partner-api-models/blob/main/models/product-fees-api-model/productFeesV0.json
* Source MD5 signature: 9b41e8371c3ebec4240a302a60c23219
*
*
* Selling Partner API for Product Fees
* The Selling Partner API for Product Fees lets you programmatically retrieve estimated fees for a product. You can then account for those fees in your pricing.
*/
namespace Sxqibo\FastSpapi\Api;
use Sxqibo\FastSpapi\Client;
class ProductFees extends Client {

  /**
  * Operation getMyFeesEstimateForSKU
  *
  * @param string $sellerSKU Used to identify an item in the given marketplace. SellerSKU is qualified by the seller's SellerId, which is included with every operation that you submit.
  *
  */
  public function getMyFeesEstimateForSKU($sellerSKU, $body = [])
  {
    return $this->send("/products/fees/v0/listings/{$sellerSKU}/feesEstimate", [
      'method' => 'POST',
      'json' => $body
    ]);
  }

  /**
  * Operation getMyFeesEstimateForASIN
  *
  * @param string $asin The Amazon Standard Identification Number (ASIN) of the item.
  *
  */
  public function getMyFeesEstimateForASIN($asin, $body = [])
  {
    return $this->send("/products/fees/v0/items/{$asin}/feesEstimate", [
      'method' => 'POST',
      'json' => $body
    ]);
  }
}