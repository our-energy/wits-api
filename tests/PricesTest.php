<?php

namespace OurEnergy\WitsApi\Tests;

use DateTimeInterface;
use OurEnergy\WitsApi\Client;
use OurEnergy\WitsApi\Enums\MarketType;
use OurEnergy\WitsApi\Enums\Schedule;
use OurEnergy\WitsApi\Models\Price;

class PricesTest extends BaseTestCase
{
    public function testCreatePrice(): void
    {
        $data = [
            "schedule" => "PRSL",
            "runType" => "G",
            "tradingDateTime" => "2022-10-28T01:00:00+13:00",
            "tradingPeriod" => 3,
            "marketType" => "E",
            "node" => "WKO0331",
            "price" => 94.52,
            "lastRunTime" => "2022-10-27T20:10:08+13:00"
        ];

        $price = Price::create($data);

        $this->assertEquals("PRSL", $price->getSchedule());
        $this->assertEquals("G", $price->getRunType());
        $this->assertEquals("2022-10-28T01:00:00+13:00", $price->getTradingDateTime()->format(DateTimeInterface::RFC3339));
        $this->assertEquals(3, $price->getTradingPeriod());
        $this->assertEquals("E", $price->getMarketType());
        $this->assertEquals("WKO0331", $price->getNode());
        $this->assertEquals(94.52, $price->getPrice());
        $this->assertEquals("2022-10-27T20:10:08+13:00", $price->getLastRunTime()->format(DateTimeInterface::RFC3339));
    }

    public function testGetPrices(): void
    {
        $mockClient = $this->getMockHttpClient(file_get_contents(__DIR__ . '/mocks/GetPrices.json'));

        $provider = new MockProvider();

        $client = new Client($provider, true, $mockClient);

        $prices = $client->getPrices(Schedule::PRSL, MarketType::E);

        $this->assertCount(6, $prices);

        $this->assertEquals("PRSL", $prices[0]->getSchedule());
        $this->assertEquals("E", $prices[0]->getMarketType());
    }
}
