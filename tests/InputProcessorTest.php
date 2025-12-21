<?php

use CliGame\Command\CommandDiscovery;
use CliGame\InputProcessor;
use CliGame\Map;
use CliGame\Character\Player;
use PHPUnit\Framework\TestCase;

class InputProcessorTest extends TestCase
{
    private InputProcessor $processor;

    protected function setUp(): void
    {
        $player = new Player('Tester');
        $map = new Map($player);
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

        $this->assertIsArray($commands);
        $this->assertContains('help', $commands);
        $this->assertContains('move', $commands);
        $this->assertContains('look', $commands);
        $this->assertContains('status', $commands);
        $this->assertContains('quit', $commands);
    }

    public function testProcessEmptyInput(): void
    {
        $result = $this->processor->process("   \n\t  ");

        $this->assertFalse($result->shouldExit());
        $this->assertEmpty($result->getOutput());
    }
}
