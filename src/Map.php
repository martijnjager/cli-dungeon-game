<?php

namespace CliGame;

use CliGame\Character\Monster;
use CliGame\Character\Monsters\Dragon;
use CliGame\Character\Monsters\Goblin;
use CliGame\Character\Monsters\Troll;
use CliGame\Enum\RoomType;
use CliGame\Character\Player;
use CliGame\Enum\MapDirection;
use CliGame\Room;
use CliGame\Service\Container;

class Map
{
    /** @var array<int,array<int,Room>> */
    private array $rooms = [];

    public function __construct(Player $player, int $numberOfEmptyRooms = 3)
    {
        $this->generateDefaultMap($player, $numberOfEmptyRooms);
    }

    private function generateDefaultMap(Player $player, int $numberOfRooms = 3): void
    {
        // 3x3 grid of rooms
        $numberOfEnemyRooms = 0;
        $numberOfTreasureRooms = 0;
        $numberOfEmptyRooms = 0;

        for ($y = 0; $y < $numberOfRooms; $y++) {
            for ($x = 0; $x < $numberOfRooms; $x++) {

                $location = new Location($x, $y);
                
                if ($player->isInRoom($location)) {
                    $this->rooms[$y][$x] = new Room(
                        RoomType::EMPTY,
                        new Location($x, $y),
                        null
                    );
                    continue;
                }

                $roomType = $this->decideRoomType(
                    $numberOfEnemyRooms,
                    $numberOfTreasureRooms,
                    $numberOfEmptyRooms
                );

                $monster = $roomType === RoomType::ENEMY ? $this->createMonsterForEnemyRoom() : null;

                $treasure = $this->calculateAmountTreasureByRoom($roomType);

                Container::getInstance()->get(TreasureTracker::class)->addTreasureByLocation($location, $treasure);

                $this->rooms[$y][$x] = new Room($roomType, $location, $monster);
            }
        }
    }

    /**
     * Decide the type of room to create based on predefined probabilities.
     * 
     * @param int &$numberOfEnemyRooms
     * @param int &$numberOfTreasureRooms
     * @param int &$numberOfEmptyRooms
     * @return RoomType
     */
    public function decideRoomType(int &$numberOfEnemyRooms, int &$numberOfTreasureRooms, int &$numberOfEmptyRooms): RoomType 
    {
        $enemyProbability = 0.3;
        $treasureProbability = 0.2;

        $rand = mt_rand() / mt_getrandmax();

        if ($rand < $enemyProbability) {
            $numberOfEnemyRooms++;
            return RoomType::ENEMY;
        } 
        
        if ($rand < $enemyProbability + $treasureProbability) {
            $numberOfTreasureRooms++;
            return RoomType::TREASURE;
        }

        $numberOfEmptyRooms++;
        return RoomType::EMPTY;
    }

    /**
     * Convert the internal Room objects into an array structure
     * compatible with GameLogic (['desc'=>..., 'enemy'=>...]).
     *
     * @return array
     */
    public function printMapInMatrixFormat(Player $player)
    {
        $mapArray = [];
        foreach ($this->rooms as $roomLayer) {
            foreach ($roomLayer as $room) {
                $isInRoom = $player->isInRoom($room->getLocation());

                $x = $room->getLocation()->getX();
                $y = $room->getLocation()->getY();
                $mapArray[$y][$x] = ($isInRoom ? 'P: ' . $player->getAttackPower() : '') .  $room->getType()->value . ' ' . ($room->hasMonster() ? $room->getMonster()->getName() : '') . " (" . $x . "," . $y . ")";
            }
        }

        return $mapArray;
    }

    public function isValidLocation(Location $location): bool
    {
        $x = $location->getX();
        $y = $location->getY();

        return isset($this->rooms[$y][$x]);
    }

    public function discoverRoom(Location $location, Player $player): void
    {
        $room = $this->getRoom($location);

        if ($room !== null && !$room->isDiscovered()) {
            $room->discover($player);
        }
    }

    public function getRoom(Location $location): ?Room
    {
        return $this->rooms[$location->getY()][$location->getX()] ?? null;
    }

    public function isRoomDiscovered(Location $location): bool
    {
        $room = $this->getRoom($location);

        return $room !== null && $room->isDiscovered();
    }
    
    public function getAdjacentRoom(Location $currentLocation, string $direction): array
    {
        $deltas = [
            MapDirection::NORTH => [0, -1],
            MapDirection::SOUTH => [0, 1],
            MapDirection::EAST => [1, 0],
            MapDirection::WEST => [-1, 0],
        ];

        $adjacentRooms = [];

        foreach ($deltas as $dir => [$dx, $dy]) {
            $newLocation = new Location(
                $currentLocation->getX() + $dx,
                $currentLocation->getY() + $dy
            );

            if ($this->isValidLocation($newLocation)) {
                $adjacentRooms[$dir] = $this->getRoom($newLocation);
            }
        }

        if ($direction && isset($adjacentRooms[$direction])) {
            return [$direction => $adjacentRooms[$direction]];
        }

        return $adjacentRooms;
    }

    private function createMonsterForEnemyRoom(): Monster
    {
        // Weighted distribution for enemy variety
        $roll = mt_rand() / mt_getrandmax();

        if ($roll < 0.3) {
            return new Goblin();
        }

        if ($roll < 0.5) {
            return new Troll();
        }

        if ($roll < 0.6) {
            return new Dragon();
        }

        // Default/fallback
        return new Goblin();
    }

    private function calculateAmountTreasureByRoom(RoomType $roomType): int
    {
        if ($roomType === RoomType::TREASURE) {
            return mt_rand(5, 20);
        }
        
        // Non-treasure rooms can have some treasure but less likely
        return mt_rand(0, 5);
    }
}
