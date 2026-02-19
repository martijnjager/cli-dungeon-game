<?php

namespace CliGame\Balancer;

class DifficultyProfile
{
    public function __construct(
        private array $enemyHitsToKill,
        private array $playerHitsToDie,
        private array $potionHealRatio,
        public readonly float $difficultyMultiplier = 1.0,
        public readonly float $monsterPowerExponent = 1.0,
        public readonly int $monsterWeightMultiplier = 10,
    )
    {
    }

    public function minimumEnemyHitsToKill(): int
    {
        return $this->enemyHitsToKill[0];
    }
    public function maximumEnemyHitsToKill(): int
    {
        return $this->enemyHitsToKill[1];
    }

    public function potionHealRatioRange(): array
    {
        return $this->potionHealRatio;
    }
}
