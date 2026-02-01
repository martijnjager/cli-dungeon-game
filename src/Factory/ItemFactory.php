<?php

namespace CliGame\Factory;

use CliGame\Balancer\DifficultyProfile;
use CliGame\Shop\Items\Potion;

class ItemFactory
{
    public static function create(String $class, int $quantity, ...$arguments): array
    {
        $items = [];
        for ($i = 0; $i < $quantity; $i++) {
            $items[] = new $class(...$arguments);
        }
        return $items;
    }

    public static function generatePotions(DifficultyProfile $difficulty, string $type, int $quantity): array
    {
        $potionHealRatio = $difficulty->potionHealRatioRange();

        switch (strtolower($type)) {
            case Potion::TYPE_MINOR_HEALING:
                $healRatio = rand($potionHealRatio[0], (int)($potionHealRatio[1] / 2));
                break;
            case Potion::TYPE_MAJOR_HEALING:
                $healRatio = rand((int)($potionHealRatio[1] / 2) + 1, $potionHealRatio[1]);
                break;
            default:
                $healRatio = rand((int)($potionHealRatio[1] / 2) + 1, $potionHealRatio[1]);
                break;
        }

        return self::create(Potion::class, $quantity, $type, 0, $healRatio);
    }
}