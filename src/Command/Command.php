<?php

namespace CliGame\Command;

use CliGame\Map;
use CliGame\Character\Player;
use CliGame\Service\Container;

abstract class Command
{
    /**
     * @var array<string, Command>
     */
    private static array $registry = [];

    protected Map $map;
    protected Player $player;
    protected Container $container;

    public function __construct(Map $map, Player $player, Container $container)
    {
        $this->map = $map;
        $this->player = $player;
        $this->container = $container;
    }

    abstract public function getName(): string;

    abstract public function help(): string;

    abstract public function execute(array $arguments): CommandResult;

    /**
     * @return array<string, Command>
     */
    public static function all(): array
    {
        return self::$registry;
    }

    public static function clearRegistry(): void
    {
        self::$registry = [];
    }

    public static function register(Command $command): void
    {
        self::$registry[$command->getName()] = $command;
    }
}
