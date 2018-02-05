<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class AliExpressClient
{

    private $client = null;

    private $ApiKey;
    private $TrackingKey;
    private $DigitalSign;




    /**
     * Client for accessing AliExpress data.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->client =  new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://gw.api.alibaba.com/openapi/param2/2/portals.open/',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        $this->ApiKey = '77485';
        $this->TrackingKey = 'mugger';
        $this->DigitalSign = 'rj7Luq7eXL';

    }


    public function getProductDetails($productId){

        $url = 'api.getPromotionProductDetail';
        $currency = 'EUR';
        $fields = 'productId,productTitle,productUrl,imageUrl,localPrice,volume,discount,allImageUrls';
        $uri = $url . '/' .$this->ApiKey.'?fields=' . $fields
                . '&localCurrency=' . $currency
                . '&productId=' . $productId;


        $response = $this->client->request('POST', $uri);
        return json_decode($response->getBody()->getContents())->result;

    }

    public function getAffiliateLink($product_url){
        $url = 'api.getPromotionLinks';
        $fields = 'promotionUrl';
        $uri = $url . '/' .$this->ApiKey.'?fields=' . $fields .
                 '&trackingId=' . $this->TrackingKey . '&urls=' . $product_url;

        $response = $this->client->request('POST', $uri);
        return json_decode($response->getBody()->getContents())->result;
    }

    public function createCloakLink($product_details){
        $link = "\n{$product_details->productId},{$product_details->affiliate_link}";
        // $file = get_home_url(null, 'go/', '') . 'redirects.txt';
        file_put_contents('/opt/lampp/htdocs/wpdev/go/redirects.txt', $link, FILE_APPEND | LOCK_EX);
        $product_details->shortUrl = "/go/{$product_details->productId}";
        return $product_details;
    }

    public function searchProductByID($productId){

        $product_details = $this->getProductDetails($productId);
        $product_link = $this->getAffiliateLink($product_details->productUrl);
        $product_details->affiliate_link = $product_link->promotionUrls[0]->promotionUrl;


        return  $this->createCloakLink($product_details);
    }




}

