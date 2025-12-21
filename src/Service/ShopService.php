<?php

namespace CliGame\Service;

use CliGame\Shop\Items\Item;

class ShopService
{
    private array $items = [];

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function removeItem(Item $item): void
    {
        $index = array_search($item, $this->items, true);
        if ($index !== false) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function buyItem(Item $item, int &$playerGold): bool
    {
        if (in_array($item, $this->items, true) && $playerGold >= $item->getPrice()) {
            $playerGold -= $item->getPrice();
            $this->removeItem($item);
            return true;
        }
        return false;
    }
}