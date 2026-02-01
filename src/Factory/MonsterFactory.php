<?php

namespace CliGame\Factory;

use CliGame\Trait\Discoverer;

class MonsterFactory
{
    use Discoverer;

    private array $monsters = [];

    public function __construct()
    {
        $this->loadMonsterTypes();
    }

    public function createMonster(string $type)
    {
        $className = 'CliGame\\Character\\Monster\\' . ucfirst($type);
        if (class_exists($className)) {
            return new $className();
        }

        throw new \Exception("Monster type '$type' does not exist.");
    }

    /**
     * Load all available monsters by class name dynamically
     */
    private function loadMonsterTypes(): void
    {
        $monsterDirectory = __DIR__ . '/../Character/Monster';
        $monsterNamespace = 'CliGame\\Character\\Monster';

        $monsterClasses = $this->discoverClasses($monsterDirectory, $monsterNamespace);

        foreach ($monsterClasses as $class) {
            $type = basename($class);
            $this->monsters[$type] = $class;
        }
    }

    public function getAvailableMonsters(): array
    {
        return array_keys($this->monsters);
    }
}