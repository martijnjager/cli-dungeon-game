<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;

class StatusCommand extends Command
{
    public function getName(): string
    {
        return 'status';
    }

    public function execute(array $arguments): CommandResult
    {
        $location = $this->player->getCurrentLocation();
        $message = 'Location: (' . $location->getX() . ',' . $location->getY() . '), Health: ' . $this->player->getCurrentHealth();
        $message .= ', Treasure Collected: ' . $this->player->getTreasureCollected();

        $inventory = $this->player->getInventory()->getItems();
        if (empty($inventory)) {
            $message .= ', Inventory: Empty';
        } else {
            $itemNames = array_map(function ($itemData) {
                return $itemData['item']->printInfo() . ' (' . $itemData['quantity'] . ')';
            }, $inventory);
            $message .= PHP_EOL . 'Inventory: ' . PHP_EOL . implode(PHP_EOL, $itemNames);
        }

        return CommandResult::continue($message);
    }

    public function help(): string
    {
        return 'Displays the player\'s current status including location and health.';
    }
}
