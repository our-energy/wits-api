<?php

declare(strict_types=1);

namespace OurEnergy\WitsApi\Models;

use DateTimeInterface;
use DateTime;
use Exception;

class Price
{
    protected string $schedule;
    protected string $runType;
    protected DateTimeInterface $tradingDateTime;
    protected int $tradingPeriod;
    protected string $marketType;
    protected string $node;
    protected float $price;
    protected DateTimeInterface $lastRunTime;

    /**
     * @return string
     */
    public function getSchedule(): string
    {
        return $this->schedule;
    }

    /**
     * @param string $schedule
     */
    public function setSchedule(string $schedule): void
    {
        $this->schedule = $schedule;
    }

    /**
     * @return string
     */
    public function getRunType(): string
    {
        return $this->runType;
    }

    /**
     * @param string $runType
     */
    public function setRunType(string $runType): void
    {
        $this->runType = $runType;
    }

    /**
     * @return DateTimeInterface
     */
    public function getTradingDateTime(): DateTimeInterface
    {
        return $this->tradingDateTime;
    }

    /**
     * @param DateTimeInterface $tradingDateTime
     */
    public function setTradingDateTime(DateTimeInterface $tradingDateTime): void
    {
        $this->tradingDateTime = $tradingDateTime;
    }

    /**
     * @return int
     */
    public function getTradingPeriod(): int
    {
        return $this->tradingPeriod;
    }

    /**
     * @param int $tradingPeriod
     */
    public function setTradingPeriod(int $tradingPeriod): void
    {
        $this->tradingPeriod = $tradingPeriod;
    }

    /**
     * @return string
     */
    public function getMarketType(): string
    {
        return $this->marketType;
    }

    /**
     * @param string $marketType
     */
    public function setMarketType(string $marketType): void
    {
        $this->marketType = $marketType;
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->node;
    }

    /**
     * @param string $node
     */
    public function setNode(string $node): void
    {
        $this->node = $node;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return DateTimeInterface
     */
    public function getLastRunTime(): DateTimeInterface
    {
        return $this->lastRunTime;
    }

    /**
     * @param DateTimeInterface $lastRunTime
     */
    public function setLastRunTime(DateTimeInterface $lastRunTime): void
    {
        $this->lastRunTime = $lastRunTime;
    }

    /**
     * @param array $data
     * @return Price
     * @throws Exception
     */
    public static function create(array $data): Price
    {
        $instance = new Price();
        $instance->setSchedule($data['schedule']);
        $instance->setRunType($data['runType']);
        $instance->setTradingDateTime(new DateTime($data['tradingDateTime']));
        $instance->setTradingPeriod($data['tradingPeriod']);
        $instance->setMarketType($data['marketType']);
        $instance->setNode($data['node']);
        $instance->setPrice($data['price']);
        $instance->setLastRunTime(new DateTime($data['lastRunTime']));

        return $instance;
    }
}