<?php

namespace CliGame\Character\Monsters;

use CliGame\Character\Monster;
use CliGame\Enum\MonsterType;

class Troll extends Monster
{
    protected MonsterType $type = MonsterType::TROLL;
    protected int $baseMinAttackPower = 10;
    protected int $baseMaxAttackPower = 20;
    
    protected bool $isBoss = false;
    protected bool $canPeek = true;

    public static function getBasePowerLevel(): int
    {
        return 2; // Medium power monster
    }
}