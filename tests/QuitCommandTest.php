<?php

use CliGame\Command\Actions\QuitCommand;
use CliGame\Map;
use CliGame\Character\Player;
use CliGame\Enum\Difficulty;
use CliGame\Service\Container;
use CliGame\TreasureTracker;
use PHPUnit\Framework\TestCase;

class QuitCommandTest extends TestCase
{
    private Player $player;
    private Map $map;
    private Container $container;
    private QuitCommand $command;

    protected function setUp(): void
    {
        $this->player = new Player('Tester');
        $this->container = Container::getInstance();
        $this->container->instance(TreasureTracker::class, new TreasureTracker());

        $difficulty = Difficulty::NORMAL->profileRanges();
        $this->map = new Map($this->player, 3, $difficulty);
        $this->command = new QuitCommand($this->map, $this->player, $this->container);
    }

    public function testGetNameReturnsQuit(): void
    {
        $this->assertSame('quit', $this->command->getName());
    }

    public function testExecuteReturnsQuitResult(): void
    {
        $result = $this->command->execute([]);

        $this->assertTrue($result->shouldExit());
        $this->assertSame('Goodbye!', $result->getOutput());
    }

    public function testHelpReturnsExpectedString(): void
    {
        $this->assertSame('Exits the game.', $this->command->help());
    }
}
