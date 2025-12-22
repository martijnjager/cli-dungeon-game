<?php

namespace CliGame\Command;

use CliGame\Map;
use CliGame\Character\Player;
use CliGame\Service\Container;
use ReflectionClass;

class CommandDiscovery
{
    private string $commandDirectory;
    private string $commandNamespace;

    public function __construct(?string $commandDirectory = null, ?string $commandNamespace = null)
    {
        $this->commandDirectory = ($commandDirectory ?? __DIR__) . DIRECTORY_SEPARATOR . 'Actions';
        $this->commandNamespace = ($commandNamespace ?? __NAMESPACE__) . '\\Actions';
    }

    /**
     * Instantiate all available commands found in the configured directory.
     *
     * @return array<string, Command>
     */
    public function load(Map $map, Player $player): array
    {
        $commands = [];
        $commandFiles = glob($this->commandDirectory . DIRECTORY_SEPARATOR . '*Command.php') ?: [];

        foreach ($commandFiles as $file) {
            $className = $this->commandNamespace . '\\' . basename($file, '.php');

            if (!class_exists($className)) {
                continue;
            }

            $reflection = new ReflectionClass($className);

            if ($reflection->isAbstract() || !$reflection->isSubclassOf(Command::class)) {
                continue;
            }

            /** @var Command $instance */
            $instance = $reflection->newInstance($map, $player, Container::getInstance());
            $commands[$instance->getName()] = $instance;
            Command::register($instance);
        }

        return $commands;
    }
}
