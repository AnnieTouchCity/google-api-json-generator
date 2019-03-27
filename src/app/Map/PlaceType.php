<?php
/**
 * Created by PhpStorm.
 * User: Annie
 * Date: 2019/3/15
 * Time: 下午 05:30
 */

namespace Map;

class PlaceType extends BasePlaceSearch
{

    private $location = '24.3452552,120.6230258';
    private $radius = 700000;

    public function __construct()
    {
        parent::__construct();
        $this->setType(['lodging', 'train_station', 'bus_station','gas_station','restaurant']);
//        $this->setType(['convenience_store', 'lodging', 'train_station', 'bus_station','gas_station','restaurant']);
        $api = "https://maps.googleapis.com/maps/api/place/search/json";


        $this->setAPI($api);
    }

    public function getMapPlaceByType()
    {

        foreach ($this->types as $type) {
            $this->setQuery([
                'location' => $this->location,
                'radius' => $this->radius,
                'language' => 'zh-tw',
                "types" => $type,
                "sensor" => false
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
            sleep(5);
            $this->query['pagetoken'] = $objList->next_page_token;
            $url = "{$this->api}?" . http_build_query($this->query);
            $objList = json_decode(file_get_contents($url));

            $this->savePlaceToDB($objList->results, $type);
            if (!$objList->next_page_token) {
                break;
            }
        }
    }
}