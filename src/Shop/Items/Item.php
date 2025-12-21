<?php

namespace CliGame\Shop\Items;

abstract class Item
{
    private string $name;
    private int $price;
    private string $description;

    public function __construct(string $name, int $price, string $description)
    {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
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

    abstract public function use();
}