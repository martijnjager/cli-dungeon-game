<?php

namespace CliGame\Character;

use CliGame\Location;
use CliGame\Trait\AttackPower;

class Player
{
    use AttackPower;

    private Location $currentLocation;

    private int $currentHealth = 100;

    private int $level = 1;

    private int $xp = 0;

    private string $name;

    private int $treasureCollected = 0;

    public function __construct(string $name, ?Location $startingLocation = null)
    {
        $this->name = $name;

        $this->currentLocation = $startingLocation ?? new Location(0, 0);
        $this->minAttackPower = 5;
        $this->maxAttackPower = 15;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCurrentLocation(): Location
    {
        return $this->currentLocation;
    }

    public function move(Location $newLocation): void
    {
        $this->currentLocation = $newLocation;
    }

    public function getCurrentHealth(): int
    {
        return $this->currentHealth;
    }

    public function isInRoom(Location $location): bool
    {
        return $this->currentLocation->equals($location);
    }

    public function collectTreasure(int $amount): void
    {
        $this->treasureCollected += $amount;
    }

    public function getTreasureCollected(): int
    {
        return $this->treasureCollected;
    }

    public function takeDamage(int $damage): void
    {
        $this->currentHealth = max(0, $this->currentHealth - $damage);
    }

    public function isAlive(): bool
    {
        return $this->currentHealth > 0;
    }
}
