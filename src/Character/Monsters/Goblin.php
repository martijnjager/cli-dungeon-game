<?php

namespace CliGame\Character\Monsters;

use CliGame\Character\Monster;
use CliGame\Enum\MonsterType;

class Goblin extends Monster
{
    protected MonsterType $type = MonsterType::GOBLIN;
    protected int $health = 30;
    protected int $minAttackPower = 1;
    protected int $maxAttackPower = 5;

    protected bool $isBoss = false;
    protected bool $canPeek = true;
}