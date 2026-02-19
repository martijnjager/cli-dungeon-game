<?php

namespace CliGame\Factory;

use CliGame\Balancer\DifficultyProfile;
use CliGame\Character\Monster;
use CliGame\Trait\Discoverer;

class MonsterFactory
{
    use Discoverer;

    private array $monsters = [];
    private DifficultyProfile $difficulty;

    public function __construct(DifficultyProfile $difficulty)
    {
        $this->difficulty = $difficulty;
        $this->loadMonsterTypes();
    }

    public function createMonster(string $type): Monster
    {
        $className = 'CliGame\\Character\\Monsters\\' . ucfirst($type);
        if (class_exists($className)) {
            return new $className($this->difficulty);
        }

        throw new \Exception("Monster type '$type' does not exist.");
    }

    /**
     * Load all available monsters by class name dynamically
     */
    private function loadMonsterTypes(): void
    {
        $monsterDirectory = __DIR__ . '/../Character/Monsters';
        $monsterNamespace = 'CliGame\\Character\\Monsters';

        $monsterClasses = $this->discoverClasses($monsterDirectory, $monsterNamespace);

        foreach ($monsterClasses as $class) {
            $type = basename(str_replace('\\', '/', $class));
            $this->monsters[$type] = $class;
        }
    }

    public function getAvailableMonsters(): array
    {
        return array_keys($this->monsters);
    }

    /**
     * Get all monsters sorted by power level
     * @return array Array of monster class names indexed by power level
     */
    public function getMonstersByPowerLevel(): array
    {
        $monstersByPower = [];
        
        foreach ($this->monsters as $type => $className) {
            if (class_exists($className)) {
                $powerLevel = $className::getBasePowerLevel();
                if (!isset($monstersByPower[$powerLevel])) {
                    $monstersByPower[$powerLevel] = [];
                }
                $monstersByPower[$powerLevel][] = $type;
            }
        }
        
        ksort($monstersByPower);
        return $monstersByPower;
    }

    /**
     * Create a random monster appropriate for the difficulty level
     * Uses weighted distribution based on difficulty multiplier and monster power
     */
    public function createRandomMonster(): Monster
    {
        $monstersByPower = $this->getMonstersByPowerLevel();
        
        if (empty($monstersByPower)) {
            throw new \Exception("No monsters available to create.");
        }
        
        // Adjust power distribution based on difficulty
        // Easy difficulty favors weaker monsters, hard difficulty favors stronger monsters
        $diffMultiplier = $this->difficulty->difficultyMultiplier;
        $powerExponent = $this->difficulty->monsterPowerExponent;
        $weightMultiplier = $this->difficulty->monsterWeightMultiplier;
        
        // Build weighted array based on power level and difficulty
        $weightedMonsters = [];
        foreach ($monstersByPower as $powerLevel => $monsters) {
            // Calculate weight: lower difficulty favors low power, higher difficulty favors high power
            if ($diffMultiplier < 1.0) {
                // Easy mode: reduce weight for higher power monsters
                $weight = 1.0 / pow($powerLevel, $powerExponent);
            } elseif ($diffMultiplier > 1.0) {
                // Hard mode: increase weight for higher power monsters
                $weight = pow($powerLevel, $powerExponent);
            } else {
                // Normal mode: balanced distribution
                $weight = 1.0;
            }
            
            foreach ($monsters as $monsterType) {
                // Add each monster with its calculated weight
                for ($i = 0; $i < max(1, (int)($weight * $weightMultiplier)); $i++) {
                    $weightedMonsters[] = $monsterType;
                }
            }
        }
        
        // Select random monster from weighted array
        $selectedType = $weightedMonsters[array_rand($weightedMonsters)];
        return $this->createMonster($selectedType);
    }
}