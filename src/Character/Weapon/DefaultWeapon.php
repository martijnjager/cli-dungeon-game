<?php

namespace CliGame\Character\Weapon;

use CliGame\Character\AttackOption;
use CliGame\Character\Character;
use CliGame\Character\Monster;
use CliGame\Character\Player;
use CliGame\Character\Weapon;

class DefaultWeapon extends Weapon
{
    protected $name = 'Default Weapon';

    protected $damage = 5;

    public function __construct()
    {
        $this->damage = 5;
        $this->name = 'Default Weapon';
        $this->attackOptions = [
            new AttackOption("Basic Attack", "A simple attack with no special effects.", 1, 3, 0.0, 100),
            new AttackOption("Heavy Strike", "A more powerful attack with a chance to miss.", 3, 5, 0.2, 60),
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function use(Player $character)
    {
        // Default weapon has no special use effect
        return;
    }
}