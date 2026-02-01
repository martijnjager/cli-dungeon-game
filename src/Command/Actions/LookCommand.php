<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;

class LookCommand extends Command
{
    public function getName(): string
    {
        return 'look';
    }

    public function execute(array $arguments): CommandResult
    {
        $this->map->discoverRoom($this->player->getCurrentLocation(), $this->player);

        $mapArr = $this->map->printMapInMatrixFormat($this->player);
        $lines = ['Map layout:'];

        foreach ($mapArr as $row) {
            $lines[] = implode(' | ', $row);
        }

        return CommandResult::continue(implode(PHP_EOL, $lines));
    }

    public function help(): string
    {
        return 'Displays the current map layout with discovered rooms.';
    }
}
