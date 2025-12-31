<?php

use CliGame\Map;
use CliGame\Character\Player;
use CliGame\Enum\MapDirection;
use CliGame\Service\Container;
use CliGame\TreasureTracker;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    protected function setUp(): void
    {
        Container::getInstance()->instance(TreasureTracker::class, new TreasureTracker());
    }

    public function testPrintMapInMatrixFormatHasCorrectStructure(): void
    {
        $player = new Player('Tester');
        $map = new Map($player, 3);
        $matrix = $map->printMapInMatrixFormat($player);

        $this->assertIsArray($matrix);
        $this->assertCount(3, $matrix, 'Matrix should be 3 rows tall.');

        foreach ($matrix as $y => $row) {
            $this->assertIsArray($row);
            $this->assertCount(3, $row, 'Each row should have 3 columns.');

            foreach ($row as $x => $cell) {
                $this->assertIsString($cell);
                $this->assertNotSame('', $cell);
                $this->assertStringContainsString("($x,$y)", $cell, 'Cell should include its coordinates.');
            }
        }
    }

    public function testIsValidLocation(): void
    {
        $player = new Player('Tester');
        $map = new Map($player, 3);

        $this->assertTrue($map->isValidLocation($player->getCurrentLocation()));
        $this->assertFalse($map->isValidLocation(new \CliGame\Location(5, 5)));
    }

    public function testDiscoverRoomMarksRoomAsDiscovered(): void
    {
        $player = new Player('Tester');
        $map = new Map($player, 3);
        $location = $player->getCurrentLocation();

        $this->assertFalse($map->isRoomDiscovered($location), 'Room should initially be undiscovered.');

        $map->discoverRoom($location, $player);

        $this->assertTrue($map->isRoomDiscovered($location), 'Room should be marked as discovered.');
    }

    public function testGetRoomReturnsCorrectRoomOrNull(): void
    {
        $player = new Player('Tester');
        $map = new Map($player, 3);
        $location = $player->getCurrentLocation();

        $room = $map->getRoom($location);
        $this->assertNotNull($room, 'Should return a Room object for a valid location.');

        $invalidLocation = new \CliGame\Location(5, 5);
        $this->assertNull($map->getRoom($invalidLocation), 'Should return null for an invalid location.');
    }

    public function testIsRoomDiscoveredReturnsCorrectStatus(): void
    {
        $player = new Player('Tester');
        $map = new Map($player, 3);
        $location = $player->getCurrentLocation();

        $this->assertFalse($map->isRoomDiscovered($location), 'Room should initially be undiscovered.');

        $map->discoverRoom($location, $player);

        $this->assertTrue($map->isRoomDiscovered($location), 'Room should be marked as discovered.');
    }

    public function testGetAdjacentRoomReturnsCorrectRooms(): void
    {
        $player = new Player('Tester');
        $map = new Map($player, 3);
        $location = $player->getCurrentLocation();

        $adjacentRooms = $map->getAdjacentRoom($location, MapDirection::EAST->value);

        $this->assertIsArray($adjacentRooms);
        $this->assertCount(1, $adjacentRooms);
        $this->assertArrayHasKey(MapDirection::EAST->value, $adjacentRooms, 'Should return room for the requested direction.');

        foreach ($adjacentRooms as $direction => $room) {
            $this->assertContains($direction, MapDirection::toArray(), 'Direction should be valid.');
            $this->assertNotNull($room, 'Adjacent room should not be null.');
        }

        $blockedDirection = MapDirection::NORTH->value;
        $fallbackRooms = $map->getAdjacentRoom($location, $blockedDirection);

        $this->assertIsArray($fallbackRooms);
        $this->assertArrayHasKey(MapDirection::EAST->value, $fallbackRooms);
        $this->assertArrayHasKey(MapDirection::SOUTH->value, $fallbackRooms);
    }
}
