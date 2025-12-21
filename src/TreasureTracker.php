<?php

namespace CliGame;

class TreasureTracker
{
    private array $treasurePerRoom = [];

    public function __construct()
    {

    }

    public function addTreasureByLocation(Location $location, int $treasure): void
    {
        $locationKey = $location->getX() . ',' . $location->getY();
        
        if (!isset($this->treasurePerRoom[$locationKey])) {
            $this->treasurePerRoom[$locationKey] = 0;
        }

        $this->treasurePerRoom[$locationKey] += $treasure;
    }

    public function removeTreasureByLocation(Location $location): void
    {
        $locationKey = $location->getX() . ',' . $location->getY();
        
        if (isset($this->treasurePerRoom[$locationKey])) {
            unset($this->treasurePerRoom[$locationKey]);
        }
    }

    public function getTreasureByLocation(Location $location): int
    {
        $locationKey = $location->getX() . ',' . $location->getY();
        
        return $this->treasurePerRoom[$locationKey] ?? 0;
    }

    public function getTotalTreasureAmount(): int
    {
        return array_sum($this->treasurePerRoom);
    }
}