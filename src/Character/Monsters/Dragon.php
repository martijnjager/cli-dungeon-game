<?php

namespace CliGame\Character\Monsters;

use CliGame\Character\Monster;
use CliGame\Enum\MonsterType;

class Dragon extends Monster
{
    protected MonsterType $type = MonsterType::DRAGON;
    protected int $baseMinAttackPower = 20;
    protected int $baseMaxAttackPower = 40;

    protected bool $isBoss = true;
    protected bool $canPeek = false;

    public static function getBasePowerLevel(): int
    {
        return 3; // Strongest monster (boss)
    }
}
