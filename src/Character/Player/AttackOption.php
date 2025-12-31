<?php

namespace CliGame\Character\Player;

use CliGame\Service\IO;

class AttackOption
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        protected string $minDamage,
        protected int $maxDamage,
        protected float $criticalChance = 0.0,
        public int $hitChance = 100,
    )
    {
    }

    public function damage(): int
    {
        $damage = rand($this->minDamage, $this->maxDamage);

        if (rand(0, 100) <= $this->criticalChance * 100) {
            $damage *= 2; // Critical hit doubles the damage
            IO::writeLine("Critical Hit! Damage doubled to {$damage}.");
        }

        return $damage;
    }

    public function print(): string
    {
        return "{$this->name}: {$this->description} (Damage: {$this->minDamage}-{$this->maxDamage})";
    }
}