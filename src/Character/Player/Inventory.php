<?php

namespace CliGame\Character\Player;

use CliGame\Shop\Items\Item;

class Inventory
{
    private array $items = [];
    private int $treasure = 0;

    public function addItem(Item $item): void
    {
        if (empty($this->items[ $item->getUniqueId() ])) {
            $this->items[$item->getUniqueId()] = [
                'item' => $item,
                'quantity' => 1
            ];

            $this->treasure -= $item->getPrice();
            
            return;
        }

        $this->items[$item->getUniqueId()] ['quantity']++;
        $this->treasure -= $item->getPrice();
    }

    public function removeItem(Item $item): void
    {
        if (empty($this->items[ $item->getUniqueId() ])) {
            return;
        }

        if ($this->items[$item->getUniqueId()]['quantity'] > 1) {
            $this->items[$item->getUniqueId()]['quantity']--;
            return;
        }

        unset($this->items[$item->getUniqueId()]);
    }

    public function getItemById(int $id): Item|null
    {
        foreach ($this->items as $item) {
            if ($item['item']->getUniqueId() == $id) {
                return $item['item'];
            }
        }

        return null;
    }

    public function getItemByName(string $name): Item|null
    {
        foreach ($this->items as $item) {
            if ($item['item']->getName() === $name) {
                return $item['item'];
            }
        }
        return null;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function collectTreasure(int $amount): void
    {
        $this->treasure += $amount;
    }

    public function getTreasureCollected(): int
    {
        return $this->treasure;
    }
}