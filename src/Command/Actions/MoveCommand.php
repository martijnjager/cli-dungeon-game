<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;
use CliGame\Character\Player;
use CliGame\Character\Monster;
use CliGame\Enum\MapDirection;
use CliGame\Location;
use CliGame\Room;
use CliGame\Service\BattleService;

class MoveCommand extends Command
{
    public function getName(): string
    {
        return 'move';
    }

    public function execute(array $arguments): CommandResult
    {
        $direction = strtolower($arguments[0] ?? '');
        $deltaDirection = MapDirection::deltaDirection($direction);

        if ($deltaDirection === null) {
            return new CommandResult(false, 'Usage: move <north|south|east|west>');
        }

        [$dx, $dy] = $deltaDirection;
        $currentLocation = $this->player->getCurrentLocation();
        $newLocation = new Location(
            $currentLocation->getX() + $dx,
            $currentLocation->getY() + $dy
        );

        if (!$this->map->isValidLocation($newLocation)) {
            return new CommandResult(false, 'You cannot move outside the map.');
        }

        $room = $this->map->getRoom($newLocation);
        $messages = ['Moved ' . $direction . ' to (' . $newLocation->getX() . ',' . $newLocation->getY() . '). Room: ' . $room->getType()->value . '.'];

        if ($room->hasMonster()) {
            $battleResult = $this->battle($room);

            $messages[] = implode(PHP_EOL, $battleResult['log']);
            if (!$battleResult['playerAlive']) {
                $messages[] = 'You have been defeated by the ' . $room->getMonster()->getName() . '!';
                return new CommandResult(true, implode(PHP_EOL, $messages));
            }

            if (!$room->getMonster()->isAlive()) {
                $messages[] = "You have defeated the " . $room->getMonster()->getName() . "!";
            }
        }

        if ($this->player->isAlive() && $room->hasTreasure()) {
            $messages[] = "You found a treasure: " . $room->getTreasureAmount() . "!";
        }
        if ($this->player->isAlive() && !$room->hasTreasure()) { 
            $messages[] = "You already robbed this room.";
        }

        $this->map->discoverRoom($newLocation, $this->player);

        return new CommandResult(false,  implode(PHP_EOL, $messages));
    }

    public function help(): string
    {
        return 'Moves the player in the specified direction (north, south, east, west).';
    }

    private function battle(Room $room)
    {
        $battleService = new BattleService();
        $battleResult = $battleService->startBattle($this->player, $room->getMonster());

        return $battleResult;
    }
}
