<?php

namespace CliGame\Character\Monsters;

use CliGame\Character\Monster;
use CliGame\Enum\MonsterType;

class Goblin extends Monster
{
    protected MonsterType $type = MonsterType::GOBLIN;
    protected int $baseMinAttackPower = 5;
    protected int $baseMaxAttackPower = 15;

    protected bool $isBoss = false;
    protected bool $canPeek = true;

    public static function getBasePowerLevel(): int
    {
        return 1; // Weakest monster
    }
}