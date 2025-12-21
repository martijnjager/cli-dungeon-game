<?php

namespace CliGame\Command\Actions;

use CliGame\Command\Command;
use CliGame\Command\CommandResult;
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
        $adjacentRooms = $this->map->getAdjacentRoom($location, $direction);
        $messages = ['Adjacent rooms:'];

        foreach ($adjacentRooms as $direction => $room) {
            if ($room->isDiscovered()) {
                $messages[] = "- {$room->getLocation()->printCoordinates()}: {$room->isDiscovered()})";
                continue;
            }

            $messages[] = "- {$room->getLocation()->printCoordinates()}: {$room->peek()}";

            if (!$room->isDiscovered() && $room->hasMonster()) {
                $messages[] = "  A monster is lurking here!";

                $caughtUserPeeking = $room->getMonster()->caughtPlayerPeeking();

                if ($caughtUserPeeking) {
                    $messages[] = "  The monster has caught you peeking! Prepare for battle!";
                    $battleResult = $this->battle($room);
                    
                    $messages = array_merge($messages, $battleResult['log']);
                    if (!$battleResult['playerAlive']) {
                        $messages[] = 'You have been defeated by the ' . $room->getMonster()->getName() . '!';
                        return new CommandResult(true, implode(PHP_EOL, $messages));
                    } else {
                        $messages[] = "  You survived the encounter while peeking.";
                    }
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

    private function battle(Room $room)
    {
        $battleService = new BattleService($this->player, $room->getMonster());
        $battleResult = $battleService->startBattle();

        return $battleResult;
    }
}