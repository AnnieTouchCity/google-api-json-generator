<?php
/**
 * Created by PhpStorm.
 * User: Annie
 * Date: 2019/3/15
 * Time: 下午 05:30
 */

namespace Map;

use Models\Place;


abstract class BasePlaceSearch
{
    private $key = '';
    protected $api = '';
    protected $query;
    protected $types;
    private $fields = ['formatted_address', 'name', 'opening_hours', 'rating'];

    abstract public function retrieveData($type);

    public function __construct()
    {
        $this->key = 'AIzaSyClSeyZ79o4I0_I8Fp699pZEOZDQPASil8';
        $this->query['fields'] = implode(',', $this->fields);
        $this->query['key'] = $this->key;

    }

    public function setAPI($api)
    {
        $this->api = $api;

    }

    public function setQuery(array $query)
    {
        $this->query = array_merge($this->query, $query);
    }

    public function setType(array $types)
    {
        $this->types = $types;
    }


    /**
     * @param $result
     * @param $type
     * @return mixed
     */
    public function savePlaceToDB($result, $type)
    {
        foreach ($result as $item) {
            $place = new Place();
            $place->place_id = $item->place_id;
            $place->lat = $item->geometry->location->lat;
            $place->lng = $item->geometry->location->lng;
            $place->name = $item->name;
            $place->address = $item->formatted_address;
            $place->type = $type;
            $place->save();
        }
    }

    public function exportToJson()
    {
        $all = Place::orderBy('type')->get();

        $typeName = [
            'gas_station' => '加油站',
            'bus_station' => '交通',
            'train_station' => '交通',
            'lodging' => '住宿歇腳',
            'restaurant' => '餐飲',
            'convenience_store' => '便利商店',
            'toilet' => '廁所'
        ];

        $objs = [];
        foreach ($typeName as $type) {
            $obj = new \stdClass();
            $obj->title = $type;
            $obj->item = [];
            $objs[] = $obj;
        }
        foreach ($all as $dbItem) {
            foreach ($objs as $obj) {
                if ($obj->title == $typeName[$dbItem->type]) {
                    $newItem = new \stdClass();
                    $newItem->lat = $dbItem->lat;
                    $newItem->log = $dbItem->lng;
                    $newItem->address = $dbItem->address;
                    $newItem->name = $dbItem->name;
                    $obj->item[] = $newItem;
                }
            }
        }
        ini_set('memory_limit', '256M');
        $fp = fopen('results.json', 'w');
        fwrite($fp, json_encode($objs));
        fclose($fp);

//        if (isset($_GET['file'])) {
//            header("Content-type:application");
//            header("Content-Length: " . (string)(filesize('results.json')));
//            header("Content-Disposition: attachment; filename=" . 'results.json');
//        }

    }

}