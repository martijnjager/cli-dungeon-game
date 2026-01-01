<?php

namespace CliGame\Character;

use CliGame\Shop\Items\Item;

abstract class Weapon extends Item
{
    protected $name;

    protected $damage;

    protected array $attackOptions = [];

    public function getAttackOptions(): array
    {
        return $this->attackOptions;
    }

    public function printAttackOptions(): void
    {
        foreach ($this->attackOptions as $key => $option) {
            if ($option instanceof AttackOption) {
                echo "{$key}. " . $option->print() . PHP_EOL;
            }
        }
    }
}