<?php

namespace Src\HotelModule;

use Src\Helpers\FetchDataFromAPIs;
use Src\Helpers\Response;

class AdvertisedHotelRooms
{
    private $hotels = [];
    private $all_rooms = [];
    private $unique_rooms_with_low_prices = [];

    /**
     * @param array $hotels
     * @void
     */
    public function AppendNewHotels(array $hotels)
    {
        $this->hotels = array_merge($this->hotels, $hotels);
    }

    /**
     * @param array $advertisers_apis
     */
    public function AddAdvertisersRooms(array $advertisers_apis)
    {
        /* Fetch data form third-party api */
        $fetchDataFromAPIs = new FetchDataFromAPIs();

        foreach ($advertisers_apis as $advertisers_api) {
            $hotels = $fetchDataFromAPIs->Get($advertisers_api);
            if (gettype($hotels) === "array" && count($hotels) > 0) {
                $this->AppendNewHotels($hotels);
            } else {
                Response::json(null, "Something Went error went reading one of advertisers apis or Check Your Internet", 404);
            }
        }
    }

    /**
     * @return array
     */
    public function getUniqueRoomsWithLowPrice()
    {
        return $this->unique_rooms_with_low_prices;
    }

    /**
     * @param $rooms
     * @return array
     */
    private function RebuildDifferentKeysObject($rooms)
    {
        $rebuildRooms = [];
        foreach ($rooms as $room) {
            if (isset($room["net_rate"]) || isset($room["totalPrice"])) {
                if (isset($room["net_rate"])) {
                    $room["net_price"] = $room["net_rate"];
                    unset($room["net_rate"]);
                }
                if (isset($room["totalPrice"])) {
                    $room["total"] = $room["totalPrice"];
                    unset($room["totalPrice"]);
                }
                $rebuildRooms[] = $room;
            } else {
                $rebuildRooms[] = $room;
            }
        }
        return $rebuildRooms;
    }

    /**
     * @void
     */
    private function PrepareRoomsFormAllAdvertisersHotels()
    {
        foreach ($this->hotels as $hotel) {
            if (isset($hotel["rooms"]) && is_array($hotel["rooms"]) && count($hotel["rooms"]) > 0) {
                $this->all_rooms = array_merge($this->all_rooms, $this->RebuildDifferentKeysObject($hotel["rooms"]));
            }
        }
    }

    /**
     * @void
     */
    private function AdjustUniqueRooms()
    {
        foreach ($this->all_rooms as $room) {
            $roomCode = $room['code'];
            if ((isset($this->unique_rooms_with_low_prices[$roomCode]) && $room['net_price'] < $this->unique_rooms_with_low_prices[$roomCode]['total']) || !isset($this->unique_rooms_with_low_prices[$roomCode])) {
                $this->unique_rooms_with_low_prices[$roomCode] = $room;
            }
        }
    }

    /**
     * @void
     */
    public function FilterUniqueRoomsDependingOnLowPrice()
    {
        if (count($this->hotels) > 0) {
            /* Prepare Rooms Form All AdvertisersHotels */
            $this->PrepareRoomsFormAllAdvertisersHotels();

            /* Adjust Unique Rooms */
            $this->AdjustUniqueRooms();
        }
    }

    /**
     * @void
     */
    public function SortUniqueRoomsFromLowPriceToHighPrice()
    {
        if (count($this->hotels) > 0) {
            usort($this->unique_rooms_with_low_prices, function ($a, $b) {
                return $a['total'] - $b['total'];
            });
        }
    }
}