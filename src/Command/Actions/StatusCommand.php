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

        return new CommandResult(false, $message);
    }

    public function help(): string
    {
        return 'Displays the player\'s current status including location and health.';
    }
}
