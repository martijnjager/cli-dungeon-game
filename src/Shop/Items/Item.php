<?php

namespace CliGame\Shop\Items;

use CliGame\Character\Player;

abstract class Item
{
    private string $name;
    private int $price;
    private string $description;

    private int $uniqueId;

    public function __construct(string $name, int $price, string $description)
    {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->uniqueId = $this->generateUniqueId();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUniqueId(): string
    {
        return $this->uniqueId;
    }

    private function generateUniqueId(): int
    {
        return spl_object_id($this);
    }

    abstract public function use(Player $player);
}