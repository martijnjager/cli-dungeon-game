<?php

namespace CliGame;

use CliGame\Command\Command;
use CliGame\Command\CommandDiscovery;
use CliGame\Command\CommandResult;
use CliGame\Character\Player;

class InputProcessor
{
    public function __construct(Map $map, Player $player, ?CommandDiscovery $commandDiscovery = null)
    {
        Command::clearRegistry();

        $discovery = $commandDiscovery ?? new CommandDiscovery();
        $discovery->load($map, $player);
    }

    public function process(string $input): CommandResult
    {
        $sanitized = $this->sanitizeInput($input);

        if ($sanitized === '') {
            return new CommandResult();
        }

        $parts = explode(' ', $sanitized);

        if (empty($parts)) {
            return new CommandResult();
        }

        $action = strtolower(array_shift($parts));

        $commands = Command::all();
        $command = $commands[$action] ?? null;

        if ($command instanceof Command === false) {
            return new CommandResult(false, 'Unknown command. Type "help" for a list of commands.');
        }

        return $command->execute($parts);
    }

    /**
        * @return array<string>
        */
    public function listCommands(): array
    {
        return array_keys(Command::all());
    }

    private function sanitizeInput(string $input): string
    {
        // Strip control characters to avoid unexpected input handling
        $clean = preg_replace('/[[:cntrl:]]+/', ' ', $input);
        $clean = preg_replace('/\s+/', ' ', $clean ?? '');

        return trim($clean ?? '');
    }
}
