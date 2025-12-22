<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;

class HelpCommand extends Command
{
    public function getName(): string
    {
        return 'help';
    }

    public function execute(array $arguments): CommandResult
    {
        $commands = Command::all();

        // Sort commands alphabetically by name
        ksort($commands);

        $messages = ['Available commands:'];
        foreach ($commands as $command) {
            $messages[] = '- ' . $command->getName() . ': ' . $command->help();
        }

        $message = implode(PHP_EOL, $messages);

        return CommandResult::continue($message);
    }

    public function help(): string
    {
        return 'Displays a list of available commands.';
    }
}
