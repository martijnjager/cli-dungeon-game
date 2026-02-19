<?php

require_once __DIR__ . '/Support/MocksDifficultyProfile.php';

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
    use MocksDifficultyProfile;

    private InputProcessor $processor;

    protected function setUp(): void
    {
        $container = Container::getInstance();
        $container->instance(TreasureTracker::class, new TreasureTracker());
        $container->bind(ShopService::class, fn() => new ShopService([]));

        $player = new Player('Tester');
        $difficultyProfile = $this->mockDifficultyProfile();
        $map = new class($player, 3, $difficultyProfile) extends Map {
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

        // $expected list of command names from the Actions directory, should be dynamically derived
        $actionDir = realpath(__DIR__ . '/../src/Command/Actions');
        $expected = [];

        if ($actionDir && is_dir($actionDir)) {
            foreach (glob($actionDir . '/*Command.php') as $file) {
                $name = basename($file, '.php'); // e.g. UseItemCommand
                $base = preg_replace('/Command$/', '', $name);
                $expected[] = strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $base));
            }
            sort($expected);
        }

        $this->assertEqualsCanonicalizing($expected, $commands);
    }

    public function testProcessEmptyInput(): void
    {
        $result = $this->processor->process("   \n\t  ");

        $this->assertFalse($result->shouldExit());
        $this->assertEmpty($result->getOutput());
    }
}
