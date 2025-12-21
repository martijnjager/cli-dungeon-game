<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;

class QuitCommand extends Command
{
    public function getName(): string
    {
        return 'quit';
    }

    public function execute(array $arguments): CommandResult
    {
        return new CommandResult(true, 'Goodbye!');
    }

    public function help(): string
    {
        return 'Exits the game.';
    }
}
