<?php

namespace CliGame\Shop\Items;

class Potion extends Item
{
    private int $healingAmount;

    public function __construct(string $name, int $price, string $description, int $healingAmount)
    {
        parent::__construct($name, $price, $description);
        $this->healingAmount = $healingAmount;
    }

    public function use()
    {
        return $this->healingAmount;
    }
}