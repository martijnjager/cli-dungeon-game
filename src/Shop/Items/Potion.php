<?php

namespace CliGame\Shop\Items;

use CliGame\Character\Player;

class Potion extends Item
{
    const TYPE_MAJOR_HEALING = 'Major Healing Potion';
    const TYPE_MINOR_HEALING = 'Minor Healing Potion';

    const DESCRIPTION = 'Restores Health Points';
    
    private int $healingAmount;

    public function __construct(string $name, int $price, int $healingAmount)
    {
        parent::__construct($name, $price, self::DESCRIPTION);
        $this->healingAmount = $healingAmount;
    }

    public function use(Player $player): void
    {
        $player->increaseHealth($this->healingAmount);
    }

    public function printInfo(): string
    {
        return "#{$this->getUniqueId()} : {$this->getName()} - {$this->getDescription()} - Amount {$this->healingAmount}";
    }

    public function printShop(): string
    {
        return "#{$this->getUniqueId()} : {$this->getName()} - {$this->getPrice()} - {$this->getDescription()} - Amount {$this->healingAmount}";
    }
}