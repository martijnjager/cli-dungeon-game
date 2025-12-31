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

        $item = $shopService->buyItemById($arguments[0], $this->player);

        if ($item === null) {
            $item = $shopService->buyItemByName($arguments[0], $this->player);
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