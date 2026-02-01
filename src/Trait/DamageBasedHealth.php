<?php

namespace CliGame\Trait;

use CliGame\Balancer\DifficultyProfile;

trait DamageBasedHealth
{
    protected int $healthPerDamageUnit = 10;
    protected int $currentHealth;

    public function calculateHealthFromHitsToDie(DifficultyProfile $difficulty, int $minAttackPower, int $maxAttackPower): int
    {
        $averageAttackPower = ($minAttackPower + $maxAttackPower) / 2;
        $requiredHitsToDie = $difficulty->maximumEnemyHitsToKill();
        return (int)ceil(($averageAttackPower * $requiredHitsToDie * $difficulty->difficultyMultiplier) / 50) * 50;
    }

    public function initializeHealth(DifficultyProfile $difficulty, int $minAttackPower, int $maxAttackPower): void
    {
        $this->currentHealth = $this->calculateHealthFromHitsToDie($difficulty, $minAttackPower, $maxAttackPower);
    }

    public function getCurrentHealth(): int
    {
        return $this->currentHealth;
    }

    public function takeDamage(int $damage): void
    {
        $this->currentHealth -= $damage;
        if ($this->currentHealth < 0) {
            $this->currentHealth = 0;
        }
    }

    public function isAlive(): bool
    {
        return $this->currentHealth > 0;
    }
}