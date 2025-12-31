<?php

use CliGame\Command\Command;
use CliGame\Command\CommandDiscovery;
use CliGame\Map;
use CliGame\Character\Player;
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
        $map = new Map($player);

        $discovery = new CommandDiscovery();
        $commands = $discovery->load($map, $player);

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

        $this->assertEqualsCanonicalizing($expected, array_keys($commands));

        $this->assertContainsOnlyInstancesOf(Command::class, $commands);
        $this->assertSame($commands, Command::all(), 'Commands should be registered globally.');
    }

    public function testNoCommandsDiscoveredInEmptyDirectory(): void
    {
        $player = new Player('Tester');
        $map = new Map($player);

        $discovery = new CommandDiscovery(__DIR__ . '/EmptyCommands', 'CliGame\\Command\\EmptyCommands');
        $commands = $discovery->load($map, $player);

        $this->assertEmpty($commands, 'No commands should be discovered in an empty directory.');
        $this->assertEmpty(Command::all(), 'No commands should be registered globally.');
    }
}
