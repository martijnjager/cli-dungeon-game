<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;

class UseItemCommand extends Command
{
    public function getName(): string
    {
        return 'use-item';
    }

    public function execute(array $arguments): CommandResult
    {
        $item = $this->player->getInventory()->getItemById($arguments[0]);

        if (empty($item)) {
            $item = $this->player->getInventory()->getItemByName($arguments[0]);
        }

        if (empty($item)) {
            return CommandResult::continue('Item not found in inventory.');
        }

        $item->use($this->player);
        $this->player->getInventory()->removeItem($item);

        return CommandResult::continue('Item has been used');
    }

    public function help(): string
    {
        return 'Use item from the player\'s inventory.';
    }
}