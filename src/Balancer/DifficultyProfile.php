<?php

namespace CliGame\Balancer;

class DifficultyProfile
{
    public function __construct(
        private array $enemyHitsToKill,
        private array $playerHitsToDie,
        private array $potionHealRatio,
        private float $damageVarianceFactor,
        private float $treasureAmountFactor
    )
    {
    }
}