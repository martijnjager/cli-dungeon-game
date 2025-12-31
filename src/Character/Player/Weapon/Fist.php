<?php

namespace CliGame\Character\Player\Weapon;

use CliGame\Character\Player;
use CliGame\Character\Player\AttackOption;
use CliGame\Character\Player\Weapon;

/**
 * Defeat a globin in 4-5 attacks
 * Defeat a troll in 8-9 attacks with luck
 * Unable to defeat a dragon
 */
class Fist extends Weapon
{
    public function __construct()
    {
        $this->name = 'Fist';
        $this->damage = 5;

        $this->attackOptions = [
            new AttackOption("Quick Punch", "A fast but less powerful punch.", 2, 4, 0.1, 90),
            new AttackOption("Strong Punch", "A slower but more powerful punch.", 3, 5, 0.2, 50),
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAttackOptions(): array
    {
        return $this->attackOptions;
    }

    public function use(Player $player)
    {
        $player->useWeapon($this);
    }
}