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

    public function findItemByName(string $name): ?Item
    {
        foreach ($this->items as $item) {
            if ($item->getName() == $name) {
                return $item;
            }
        }
        return null;
    }

    public function findItemById(int $id): ?Item
    {
        foreach ($this->items as $item) {
            if ($item->getUniqueId() == $id) {
                return $item;
            }
        }

        return null;
    }

    public function canBuyItem(Item $item, int $playerGold): bool
    {
        return in_array($item, $this->items, true) && $playerGold >= $item->getPrice();
    }

    public function buyItemByName(string $name, int &$playerGold): ?Item
    {
        $item = $this->findItemByName($name);
        
        if ($item === null) {
            return null;
        }

        foreach ($this->items as $shopItem) {
            if ($shopItem->getName() == $name) {
                $item = $shopItem;
                break;
            }
        }

        if (!empty($item) && $playerGold >= $item->getPrice()) {
            $playerGold -= $item->getPrice();
            return $item;
        }

        return null;
    }

    public function buyItemById(int $id, int &$playerGold): ?Item
    {
        $item = $this->findItemById($id);
        if ($item && $playerGold >= $item->getPrice()) {
            $playerGold -= $item->getPrice();

            return $item;
        }
        return null;
    }
}