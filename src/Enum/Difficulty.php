<?php

namespace CliGame\Enum;

use CliGame\Balancer\DifficultyProfile;

enum Difficulty: string
{
    case EASY = 'easy';
    case NORMAL = 'normal';
    case HARD = 'hard';

    public static function fromString(string $value): Difficulty
    {
        return match (strtolower($value)) {
            'easy' => self::EASY,
            'hard' => self::HARD,
            default => self::NORMAL,
        };
    }

    public function profileRanges(): DifficultyProfile
    {
        return match($this) {
            self::EASY => new DifficultyProfile(
                enemyHitsToKill: [3, 4],
                playerHitsToDie: [8, 10],
                potionHealRatio: [0.4, 0.5],
                difficultyMultiplier: 0.8,
                monsterPowerExponent: 1.5,
                monsterWeightMultiplier: 10,
            ),
            self::HARD => new DifficultyProfile(
                enemyHitsToKill: [5, 6],
                playerHitsToDie: [5, 6],
                potionHealRatio: [0.25, 0.35],
                difficultyMultiplier: 1.2,
                monsterPowerExponent: 1.5,
                monsterWeightMultiplier: 10,
            ),
            default => new DifficultyProfile(
                enemyHitsToKill: [4, 5],
                playerHitsToDie: [6, 8],
                potionHealRatio: [0.3, 0.4],
                difficultyMultiplier: 1.0,
                monsterPowerExponent: 1.0,
                monsterWeightMultiplier: 10,
            ),
        };
    }
}