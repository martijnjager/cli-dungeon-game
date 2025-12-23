<?php

namespace CliGame\Service;

use CliGame\Character\Player;
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
            if ($item->is($name)) {
                return $item;
            }
        }
        return null;
    }

    public function findItemById(int $id): ?Item
    {
        foreach ($this->items as $item) {
            if ($item->is($id)) {
                return $item;
            }
        }

        return null;
    }

    public function buyItemByName(string $name, Player $player): ?Item
    {
        $item = $this->findItemByName($name);
        
        if ($item === null) {
            return null;
        }

        foreach ($this->items as $shopItem) {
            if ($shopItem->is($name)) {
                $item = $shopItem;
                break;
            }
        }

        if (!empty($item) && $player->canAfford($item)) {
            $player->buyItem($item);
            return $item;
        }

        return null;
    }

    public function buyItemById(int $id, Player $player): ?Item
    {
        $item = $this->findItemById($id);
        if ($item && $player->canAfford($item)) {
            $player->buyItem($item);

            return $item;
        }
        return null;
    }
}