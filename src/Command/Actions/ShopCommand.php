<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;
use CliGame\Service\ShopService;

class ShopCommand extends Command
{
    public function getName(): string
    {
        return 'shop';
    }

    public function execute(array $arguments): CommandResult
    {
        $shopService = $this->container->get(ShopService::class);

        $items = $shopService->getItems();
        $itemsForSale = [];
        foreach ($items as $item) {
            $itemsForSale[] = '#' . $item->getUniqueId() . ': ' . $item->getName() . ' - ' . $item->getPrice() . ' gold ' . ' - ' . $item->getDescription();
        }

        return CommandResult::continue(implode(PHP_EOL, $itemsForSale));
    }

    public function help(): string
    {
        return 'Access the shop to buy items and upgrades. (Currently under construction)';
    }
}