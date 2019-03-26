<?php
/**
 * Created by PhpStorm.
 * User: Annie
 * Date: 2019/3/19
 * Time: 下午 02:01
 */

namespace Map\Place;


class PlaceDetail
{
    private $api = 'https://maps.googleapis.com/maps/api/place/details/json';
    private $key = '';
    private $field = ['placeid' => '', 'fields' => 'name,rating,formatted_phone_number'];


    public function __construct()
    {
        $this->key = env('GOOGLE_KEY');
        $field['key'] = $this->key;

    }

}