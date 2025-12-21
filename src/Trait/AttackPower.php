<?php

namespace CliGame\Trait;

trait AttackPower
{
    protected int $minAttackPower;
    protected int $maxAttackPower;

    protected int $agility = 1;

    public function getAttackPower(): int
    {
        return rand($this->minAttackPower, $this->maxAttackPower);
    }

    public function getMinAttackPower(): int
    {
        return $this->minAttackPower;
    }

    public function getMaxAttackPower(): int
    {
        return $this->maxAttackPower;
    }

    public function getAgility(): int
    {
        return $this->agility;
    }
}