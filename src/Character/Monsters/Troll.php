<?php

namespace CliGame\Character\Monsters;

use CliGame\Character\Monster;
use CliGame\Enum\MonsterType;

class Troll extends Monster
{
    protected MonsterType $type = MonsterType::TROLL;
    protected int $health = 50;
    protected int $minAttackPower = 5;
    protected int $maxAttackPower = 10;
    
    protected bool $isBoss = false;
    protected bool $canPeek = true;
}