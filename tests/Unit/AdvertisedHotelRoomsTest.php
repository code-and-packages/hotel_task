<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Src\HotelModule\AdvertisedHotelRooms;

class AdvertisedHotelRoomsTest extends TestCase
{
    protected $advertisers_apis;
    protected $advertisers_hotels;

    /**
     * @void
     */
    protected function setUp(): void
    {
        $this->advertisers_apis = [
            "https://coresolutions.app/php_task/api/api_v1.php", // advertiser 1
            "https://coresolutions.app/php_task/api/api_v2.php", // advertiser 2
            "https://coresolutions.app/php_task/api/api_v3.php", // advertiser 3
        ];

        $this->advertisers_hotels = new AdvertisedHotelRooms();
        $this->advertisers_hotels->AddAdvertisersRooms($this->advertisers_apis);
        $this->advertisers_hotels->FilterUniqueRoomsDependingOnLowPrice();
    }

    /**
     * @void
     * @test There is repeated room codes
     */
    public function testFilterNotUniqueRoomsWithLowPrices(): void
    {
        $UniqueRoomsWithLowPrices = $this->advertisers_hotels->getAllRooms();
        $isNotRepeatedRoomCode = true;
        $codes = [];
        foreach ($UniqueRoomsWithLowPrices as $UniqueRoomsWithLowPrice) {
            if (isset($codes[$UniqueRoomsWithLowPrice["code"]])) {
                $isNotRepeatedRoomCode = false;
                break;
            }
            $codes[$UniqueRoomsWithLowPrice["code"]] = true;
        }
        $this->assertFalse($isNotRepeatedRoomCode);
    }

    /**
     * @void
     * @test There is (Not) repeated room codes
     * that never show the exact same room more than once
     */
    public function testFilterUniqueRoomsWithLowPrices(): void
    {
        $UniqueRoomsWithLowPrices = $this->advertisers_hotels->getUniqueRoomsWithLowPrices();
        $isNotRepeatedRoomCode = true;
        $codes = [];
        foreach ($UniqueRoomsWithLowPrices as $UniqueRoomsWithLowPrice) {
            if (isset($codes[$UniqueRoomsWithLowPrice["code"]])) {
                $isNotRepeatedRoomCode = false;
                break;
            }
            $codes[$UniqueRoomsWithLowPrice["code"]] = true;
        }
        $this->assertTrue($isNotRepeatedRoomCode);
    }

    /**
     * @void
     * @test Unique Rooms (With) Sorting From Low Price To High Price
     */
    public function testUniqueRoomsWithSortingFromLowPriceToHighPrice(): void
    {
        $this->advertisers_hotels->SortUniqueRoomsFromLowPriceToHighPrice(); // Sorting From Low Price To High Price
        $UniqueRoomsWithLowPrices = $this->advertisers_hotels->getUniqueRoomsWithLowPrices();
        $isSortedFromLowPriceToLow = true;
        if ($this->count($UniqueRoomsWithLowPrices) > 0) {
            $currentPrice = 0;
            foreach ($UniqueRoomsWithLowPrices as $UniqueRoomsWithLowPrice) {
                $price = (double)$UniqueRoomsWithLowPrice["total"];
                if ($price >= $currentPrice) {
                    $currentPrice = $price;
                } else {
                    $isSortedFromLowPriceToLow = false;
                    break;
                }
            }
        }
        $this->assertTrue($isSortedFromLowPriceToLow);
    }

    /**
     * @void
     * @test Unique Rooms (With) Sorting From High Price To Low Price
     */
    public function testUniqueRoomsWithSortingFromHighPriceToLowPrice(): void
    {
        $this->advertisers_hotels->SortUniqueRoomsFromHighPriceToLowPrice(); // Sorting From Low Price To High Price
        $UniqueRoomsWithLowPrices = $this->advertisers_hotels->getUniqueRoomsWithLowPrices();
        $isSortedFromHighPriceToLowPrice = true;
        if ($this->count($UniqueRoomsWithLowPrices) > 0) {
            $currentPrice = (double)$UniqueRoomsWithLowPrices[0]["total"];
            foreach ($UniqueRoomsWithLowPrices as $UniqueRoomsWithLowPrice) {
                $price = (double)$UniqueRoomsWithLowPrice["total"];
                if ($price <= $currentPrice) {
                    $currentPrice = $price;
                } else {
                    $isSortedFromHighPriceToLowPrice = false;
                    break;
                }
            }
        }
        $this->assertTrue($isSortedFromHighPriceToLowPrice);
    }
}