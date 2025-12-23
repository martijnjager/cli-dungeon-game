<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;
use CliGame\Enum\MapDirection;
use CliGame\Service\BattleService;
use CliGame\Room;

class PeekCommand extends Command
{
    public function getName(): string
    {
        return 'peek';
    }

    public function execute(array $arguments): CommandResult
    {
        $direction = strtolower($arguments[0] ?? '');
        $location = $this->player->getCurrentLocation();

        if (!array_key_exists($direction, MapDirection::allDeltaDirections())) {
            return CommandResult::continue( 'Usage: peek <north|south|east|west>');
        }

        $adjacentRooms = $this->map->getAdjacentRoom($location, $direction);
        $messages = ['Adjacent rooms:'];

        foreach ($adjacentRooms as $direction => $room) {
            if ($room->isDiscovered()) {
                $messages[] = "- {$room->getLocation()->printCoordinates()}: {$room->isDiscovered()})";
                continue;
            }

            $messages[] = "- {$room->getLocation()->printCoordinates()}: {$room->peek()} (Treasure: {$room->getTreasureAmount()})";

            if (!$room->isDiscovered() && $room->hasMonster()) {
                $messages[] = "  A monster is lurking here!";

                $caughtUserPeeking = $room->getMonster()->caughtPlayerPeeking();

                if ($caughtUserPeeking) {
                    $this->failedPeek($room, $messages);

                    if (!$this->player->isAlive()) {
                        return new CommandResult(true, implode(PHP_EOL, $messages));
                    }

                    if ($room->hasTreasure()) {
                        $messages[] = "You found a treasure: " . $room->getTreasureAmount() . "!";
                    }

                    $this->map->discoverRoom($room->getLocation(), $this->player);
                } else {
                    $messages[] = "  You managed to peek without being noticed.";
                }
            }
        }

        $message = implode(PHP_EOL, $messages);

        return new CommandResult(false, $message);
    }

    public function help(): string
    {
        return 'Displays the status of adjacent room by direction input (discovered or undiscovered).';
    }

    private function failedPeek(Room $room, array &$messages)
    {
        $messages[] = "  The monster has caught you peeking! Prepare for battle!";
        $battleResult = $this->battle($room);
        
        $messages = array_merge($messages, $battleResult->getLog());
        if (!$battleResult->playerSurvived()) {
            $messages[] = 'You have been defeated by the ' . $room->getMonster()->getName() . '!';
        } else {
            $messages[] = "  You survived the encounter while peeking.";
        }
    }

    private function battle(Room $room)
    {
        $battleService = new BattleService();
        $battleResult = $battleService->startBattle($this->player, $room->getMonster());

        return $battleResult;
    }
}