<?php

use CliGame\Command\Command;
use CliGame\Command\CommandDiscovery;
use CliGame\Map;
use CliGame\Character\Player;
use PHPUnit\Framework\TestCase;

class CommandDiscoveryTest extends TestCase
{
    protected function setUp(): void
    {
        Command::clearRegistry();
    }

    public function testDiscoversActionCommands(): void
    {
        $player = new Player('Tester');
        $map = new Map($player);

        $discovery = new CommandDiscovery();
        $commands = $discovery->load($map, $player);

        $this->assertArrayHasKey('help', $commands);
        $this->assertArrayHasKey('move', $commands);
        $this->assertArrayHasKey('look', $commands);
        $this->assertArrayHasKey('status', $commands);
        $this->assertArrayHasKey('quit', $commands);

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
