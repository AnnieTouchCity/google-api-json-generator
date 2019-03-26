<?php


require './src/start.php';


use Map\PlaceKeyword;
use Map\PlaceType;
use Models\Place;

$ret = Place::truncate();
//$search = new PlaceKeyword();
//$search->getMapPlaceByKeyWord(['廁所' => 'toilet', 'Toilet' => 'toilet']);

$type = new PlaceType();
$type->getMapPlaceByType();

$type->exportToJson();


