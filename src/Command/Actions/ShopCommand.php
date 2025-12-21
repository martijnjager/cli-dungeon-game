<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;

class ShopCommand extends Command
{
    public function getName(): string
    {
        return 'shop';
    }

    public function execute(array $arguments): CommandResult
    {
        return new CommandResult(false, 'Shop is currently under construction.');
    }

    public function help(): string
    {
        return 'Access the shop to buy items and upgrades. (Currently under construction)';
    }
}