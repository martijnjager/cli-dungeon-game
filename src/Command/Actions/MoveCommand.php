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
            return CommandResult::continue( 'Usage: move <north|south|east|west>');
        }

        [$dx, $dy] = $deltaDirection;
        $currentLocation = $this->player->getCurrentLocation();
        $newLocation = new Location(
            $currentLocation->getX() + $dx,
            $currentLocation->getY() + $dy
        );

        if (!$this->map->isValidLocation($newLocation)) {
            return CommandResult::continue( 'You cannot move outside the map.');
        }

        $room = $this->map->getRoom($newLocation);
        $messages = ['Moved ' . $direction . ' to (' . $newLocation->getX() . ',' . $newLocation->getY() . '). Room: ' . $room->getUndiscoveredType()->value . '.'];

        if ($room->hasMonster()) {
            $battleResult = $this->battle($room);

            $messages[] = implode(PHP_EOL, $battleResult->getLog());
            if (!$battleResult->playerSurvived()) {
                $messages[] = 'You have been defeated by the ' . $room->getMonster()->getName() . '!';
                return CommandResult::quit( implode(PHP_EOL, $messages));
            }

            if (!$battleResult->monsterSurvived()) {
                $messages[] = "You have defeated the " . $room->getMonster()->getName() . "!";
            }
        }

        if ($this->player->isAlive() && $room->hasTreasure()) {
            $messages[] = $room->hasTreasure() ? "You found a treasure: " . $room->getTreasureAmount() . "!" : "No treasure in this room.";
            $treasure = $room->collectTreasure();
    
            $this->player->collectTreasure($treasure);
        }

        $this->map->discoverRoom($newLocation, $this->player);

        return CommandResult::continue(  implode(PHP_EOL, $messages));
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
