<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . './vendor/autoload.php';


use Src\HotelModule\AdvertisedHotelRooms;
use Src\Helpers\Response;

function fire()
{
    $advertisers_apis = [
        "https://coresolutions.app/php_task/api/api_v1.php", // advertiser 1
        "https://coresolutions.app/php_task/api/api_v2.php", // advertiser 2
        "https://coresolutions.app/php_task/api/api_v3.php", // advertiser 3
    ];

    $advertisers_hotels = new AdvertisedHotelRooms();

    /* 1- Filter Unique Rooms With Low Prices */
    $advertisers_hotels->AddAdvertisersRooms($advertisers_apis);

    /* 2- Filter Unique Rooms With Low Prices */
    $advertisers_hotels->FilterUniqueRoomsDependingOnLowPrice();

    /* 3- Sort Unique Rooms From Low Price To High Price*/
    $advertisers_hotels->SortUniqueRoomsFromLowPriceToHighPrice();

    Response::json($advertisers_hotels->getUniqueRoomsWithLowPrices(), "success", 200);
}


fire();