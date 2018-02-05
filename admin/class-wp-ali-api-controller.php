<?php


use AliexApi\Configuration\GenericConfiguration;
use AliexApi\AliexIO;
use AliexApi\Operations\ListProducts;

class AliapiController extends Controller
{
    public function aliconfig($conf)
    {
        $conf
            ->setApiKey('ALI_API_KEY')
            ->setTrackingKey('ALI_TRACKING_ID')
            ->setDigitalSign('ALI_DIGITAL_SIGNATURE');
            return $conf;
    }

    public function index()
    {
        $this->searchItems();
    }

    public function searchItems()
    {
        $lppfields = [
            // 'categoryId' => '1501',
            'keywords' => 'baby shoes',
            ];
        $array = $this->listPromotionProduct($lppfields);
        dd($array);
    }

    public function listPromotionProduct($lppfields)
    {
        $conf = new GenericConfiguration();
        $this->aliconfig($conf);
        $aliexIO = new AliexIO($conf);

        $listproducts = new ListProducts();
        $listproducts->setFields('productId,productTitle,productUrl,imageUrl');
        $listproducts->setKeywords($lppfields['keywords']);
        // $listproducts->setCategoryId($lppfields['categoryId']);
        $listproducts->setHighQualityItems('true');

        $formattedResponse = $aliexIO->runOperation($listproducts);
        $array = json_decode($formattedResponse, true);

        $array = array_merge($array, $lppfields);

        return $array;

    }
}