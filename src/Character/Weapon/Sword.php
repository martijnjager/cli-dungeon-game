<?php

namespace CliGame\Character\Player\Weapon;

use CliGame\Character\Player;
use CliGame\Character\Weapon;

/**
 * Defeat a globin in 2-3 attacks
 * Defeat a troll in 5-6 attacks with luck
 * Unable to defeat a dragon
 */
class Sword extends Weapon
{
    protected $damage = 15;

    public function __construct()
    {
        $this->name = 'Sword';
    }

    public function getAttackOptions(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function use(Player $player)
    {
        
    }
}
