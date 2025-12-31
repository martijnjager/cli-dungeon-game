<?php

use CliGame\Command\Actions\MoveCommand;
use CliGame\Location;
use CliGame\Map;
use CliGame\Character\Player;
use CliGame\Enum\RoomType;
use CliGame\Service\Container;
use CliGame\Service\ShopService;
use CliGame\TreasureTracker;
use PHPUnit\Framework\TestCase;

class MoveCommandTest extends TestCase
{
    private Player $player;
    private Map $map;
    private Container $container;
    private MoveCommand $command;

    protected function setUp(): void
    {
        $this->player = new Player('Tester');
        $this->container = Container::getInstance();
        $this->container->instance(TreasureTracker::class, new TreasureTracker());
        $this->container->bind(ShopService::class, fn() => new ShopService([]));

        $this->map = $this->createPeacefulMap($this->player, 3);
        $this->command = new MoveCommand($this->map, $this->player, $this->container);
    }

    public function testMovesEastUpdatesLocationAndDiscoversRoom(): void
    {
        $result = $this->command->execute(['east']);

        $this->assertFalse($result->shouldExit());
        $this->assertStringContainsString('Moved east to (1,0)', $result->getOutput() ?? '');

        $location = $this->player->getCurrentLocation();
        $this->assertSame(1, $location->getX());
        $this->assertSame(0, $location->getY());

        $room = $this->map->getRoom($location);
        $this->assertNotNull($room);
        $this->assertTrue($room->isDiscovered(), 'Moved-to room should be marked discovered.');
    }

    public function testInvalidDirectionReturnsUsageMessage(): void
    {
        $initial = $this->player->getCurrentLocation();

        $result = $this->command->execute(['upwards']);

        $this->assertFalse($result->shouldExit());
        $this->assertStringContainsString('Usage: move', $result->getOutput() ?? '');

        $current = $this->player->getCurrentLocation();
        $this->assertSame($initial->getX(), $current->getX());
        $this->assertSame($initial->getY(), $current->getY(), 'Player should not move on invalid input.');
    }

    public function testCannotMoveOutsideMap(): void
    {
        $initial = $this->player->getCurrentLocation();

        $result = $this->command->execute(['north']);

        $this->assertFalse($result->shouldExit());
        $this->assertStringContainsString('cannot move outside the map', strtolower($result->getOutput() ?? ''));

        $current = $this->player->getCurrentLocation();
        $this->assertSame($initial->getX(), $current->getX());
        $this->assertSame($initial->getY(), $current->getY(), 'Player should remain in place when movement is blocked.');
    }

    private function createPeacefulMap(Player $player, int $size): Map
    {
        return new class($player, $size) extends Map {
            public function decideRoomType(int &$numberOfEnemyRooms, int &$numberOfTreasureRooms, int &$numberOfEmptyRooms): RoomType
            {
                $numberOfEmptyRooms++;
                return RoomType::EMPTY;
            }
        };
    }
}
