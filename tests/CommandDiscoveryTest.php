<?php

use CliGame\Command\Command;
use CliGame\Command\CommandDiscovery;
use CliGame\Map;
use CliGame\Character\Player;
use CliGame\Enum\Difficulty;
use CliGame\Service\Container;
use CliGame\Service\ShopService;
use CliGame\TreasureTracker;
use PHPUnit\Framework\TestCase;

class CommandDiscoveryTest extends TestCase
{
    protected function setUp(): void
    {
        Command::clearRegistry();
        $container = Container::getInstance();
        $container->instance(TreasureTracker::class, new TreasureTracker());
        $container->bind(ShopService::class, fn() => new ShopService([]));
    }

    public function testDiscoversActionCommands(): void
    {
        $player = new Player('Tester');
        $difficulty = Difficulty::NORMAL->profileRanges();
        $map = new Map($player, 3, $difficulty);

        $discovery = new CommandDiscovery();
        $commands = $discovery->load($map, $player);

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

        $this->assertEqualsCanonicalizing($expected, array_keys($commands));

        $this->assertContainsOnlyInstancesOf(Command::class, $commands);
        $this->assertSame($commands, Command::all(), 'Commands should be registered globally.');
    }

    public function testNoCommandsDiscoveredInEmptyDirectory(): void
    {
        $player = new Player('Tester');
        $difficulty = Difficulty::NORMAL->profileRanges();
        $map = new Map($player, 3, $difficulty);

        $discovery = new CommandDiscovery(__DIR__ . '/EmptyCommands', 'CliGame\\Command\\EmptyCommands');
        $commands = $discovery->load($map, $player);

        $this->assertEmpty($commands, 'No commands should be discovered in an empty directory.');
        $this->assertEmpty(Command::all(), 'No commands should be registered globally.');
    }
}
