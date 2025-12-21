<?php

namespace CliGame\Character\Monsters;

use CliGame\Character\Monster;
use CliGame\Enum\MonsterType;

class Dragon extends Monster
{
    protected MonsterType $type = MonsterType::DRAGON;
    protected int $health = 100;
    protected int $minAttackPower = 10;
    protected int $maxAttackPower = 20;

    protected bool $isBoss = true;
    protected bool $canPeek = false;
}
