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
        if (empty($arguments)) {
            return CommandResult::continue('Please specify an item to buy.');
        }

        $shopService = $this->container->get(ShopService::class);
        $itemIdentifier = $arguments[0];

        $item = null;
        if (is_numeric($itemIdentifier)) {
            $item = $shopService->buyItemById((int)$itemIdentifier, $this->player);
        }

        if ($item === null) {
            $item = $shopService->buyItemByName((string)$itemIdentifier, $this->player);
        }

        if ($item === null) {
            return CommandResult::continue('Item not found or insufficient gold.');
        }

        return CommandResult::continue('You have successfully purchased: ' . $item->getName());
    }

    public function help(): string
    {
        return 'Buy items from the shop. (Currently under construction)';
    }
}