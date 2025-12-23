<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;

class InventoryCommand extends Command
{
    public function getName(): string
    {
        return 'inventory';
    }

    public function execute(array $arguments): CommandResult
    {
        $items = $this->player->getInventory()->getItems();
        if (empty($items)) {
            return CommandResult::continue('Your inventory is empty.');
        }

        $itemLines = [];

        foreach ($items as $itemData) {
            $item = $itemData['item'];
            $itemLines[] = $item->printInfo() . ' (' . $itemData['quantity'] . ')';
        }

        return CommandResult::continue(implode(PHP_EOL, $itemLines));
    }

    public function help(): string
    {
        return 'Displays the items in the player\'s inventory.';
    }
}