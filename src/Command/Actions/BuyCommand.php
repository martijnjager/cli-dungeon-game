<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;
use CliGame\Service\ShopService;

class BuyCommand extends Command
{
    public function getName(): string
    {
        return 'buy';
    }

    public function execute(array $arguments): CommandResult
    {
        $shopService = $this->container->get(ShopService::class);
        $treasure = $this->player->getTreasureCollected();

        $item = $shopService->buyItemById($arguments[0], $treasure);

        if ($item === null) {
            $item = $shopService->buyItemByName($arguments[0], $treasure);
        }

        if ($item === null) {
            return CommandResult::continue('Item not found or insufficient gold.');
        }

        $this->player->addToInventory($item);

        return CommandResult::continue('You have successfully purchased: ' . $item->getName());
    }

    public function help(): string
    {
        return 'Buy items from the shop. (Currently under construction)';
    }
}