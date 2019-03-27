<?php
/**
 * Created by PhpStorm.
 * User: Annie
 * Date: 2019/3/15
 * Time: 下午 05:30
 */

namespace Map;

class PlaceKeyword extends BasePlaceSearch
{

    private $location = '24.3452552,120.6230258';


    public function __construct()
    {
        parent::__construct();
        $api = "https://maps.googleapis.com/maps/api/place/textsearch/json";
        $this->setAPI($api);
    }

    public function getMapPlaceByKeyWord(array $keywords)
    {

        foreach ($keywords as $keyword => $type) {
            $this->setQuery([
                'location' => $this->location,
                'radius' => 70000,
                'language' => 'zh-tw',
                "query" => $keyword,
            ]);
            $this->retrieveData($type);
        }

        var_dump("done");
    }

    public function retrieveData($type)
    {

        if ($this->query['pagetoken']) {
            unset($this->query['pagetoken']);
        }

        $url = "{$this->api}?" . http_build_query($this->query);

        $objList = json_decode(file_get_contents($url));

        $this->savePlaceToDB($objList->results, $type);


        while ($objList->next_page_token) {
            sleep(10);
            $this->query['pagetoken'] = $objList->next_page_token;
            $url = "{$this->api}?" . http_build_query($this->query);

            $objList = json_decode(file_get_contents($url));
            $this->savePlaceToDB($objList->results, $type);
//            if (!$objList->next_page_token) {
//                break;
//            }
        }
    }
}