<?php

use CliGame\Command\CommandDiscovery;
use CliGame\InputProcessor;
use CliGame\Map;
use CliGame\Character\Player;
use CliGame\Enum\RoomType;
use CliGame\Service\Container;
use CliGame\Service\ShopService;
use CliGame\TreasureTracker;
use PHPUnit\Framework\TestCase;

class InputProcessorTest extends TestCase
{
    private InputProcessor $processor;

    protected function setUp(): void
    {
        $container = Container::getInstance();
        $container->instance(TreasureTracker::class, new TreasureTracker());
        $container->bind(ShopService::class, fn() => new ShopService([]));

        $player = new Player('Tester');
        $map = new class($player) extends Map {
            public function decideRoomType(int &$numberOfEnemyRooms, int &$numberOfTreasureRooms, int &$numberOfEmptyRooms): RoomType
            {
                $numberOfEmptyRooms++;
                return RoomType::EMPTY;
            }
        };

        $this->processor = new InputProcessor($map, $player, new CommandDiscovery());
    }

    public function testProcessesKnownCommand(): void
    {
        $result = $this->processor->process("  move   east  ");

        $this->assertFalse($result->shouldExit());
        $this->assertNotEmpty($result->getOutput());
    }

    public function testSanitizesControlCharacters(): void
    {
        $result = $this->processor->process("\t\nhelp\u{0001}");

        $this->assertFalse($result->shouldExit());
        $this->assertStringContainsString('Available commands', $result->getOutput());
    }

    public function testUnknownCommand(): void
    {
        $result = $this->processor->process('unknowncmd');

        $this->assertFalse($result->shouldExit());
        $this->assertStringContainsString('Unknown command', $result->getOutput());
    }

    public function testListCommands(): void
    {
        $commands = $this->processor->listCommands();

        $expected = [
            'buy',
            'help',
            'inventory',
            'look',
            'move',
            'peek',
            'quit',
            'shop',
            'status',
            'use-item',
        ];

        $this->assertEqualsCanonicalizing($expected, $commands);
    }

    public function testProcessEmptyInput(): void
    {
        $result = $this->processor->process("   \n\t  ");

        $this->assertFalse($result->shouldExit());
        $this->assertEmpty($result->getOutput());
    }
}
