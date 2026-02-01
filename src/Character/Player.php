<?php

namespace CliGame\Character;

use CliGame\Character\Player\Inventory;
use CliGame\Character\Weapon;
use CliGame\Character\Weapon\Fist;
use CliGame\Location;
use CliGame\Shop\Items\Item;
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

    private Inventory $inventory;

    protected Weapon $activeWeapon;

    public function __construct(string $name, ?Location $startingLocation = null)
    {
        $this->name = $name;

        $this->currentLocation = $startingLocation ?? new Location(0, 0);
        $this->minAttackPower = 5;
        $this->maxAttackPower = 15;
        $this->inventory = new Inventory();

        $this->useWeapon(new Fist());
    }

    public function useWeapon(Weapon $weapon)
    {
        $this->activeWeapon = $weapon;
    }

    public function getActiveWeapon(): Weapon
    {
        return $this->activeWeapon;
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
        $this->inventory->collectTreasure($amount);
    }

    public function getTreasureCollected(): int
    {
        return $this->inventory->getTreasureCollected();
    }

    public function takeDamage(int $damage): void
    {
        $this->currentHealth = max(0, $this->currentHealth - $damage);
    }

    public function isAlive(): bool
    {
        return $this->currentHealth > 0;
    }

    public function getInventory(): Inventory
    {
        return $this->inventory;
    }

    public function addToInventory(Item $item): void
    {
        $this->inventory->addItem($item);
    }

    public function buyItem(Item $item): void
    {
        $this->inventory->deduct($item->getPrice());
        $this->addToInventory($item);
    }

    public function increaseHealth(int $amount): void
    {
        $this->currentHealth += $amount;

        if ($this->currentHealth > 100) {
            $this->currentHealth = 100;
        }
    }

    public function canAfford(Item $item)
    {
        return $this->getTreasureCollected() >= $item->getPrice();
    }
}
