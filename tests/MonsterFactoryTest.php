<?php

use CliGame\Factory\MonsterFactory;
use CliGame\Enum\Difficulty;
use CliGame\Character\Monsters\Goblin;
use CliGame\Character\Monsters\Troll;
use CliGame\Character\Monsters\Dragon;
use PHPUnit\Framework\TestCase;

class MonsterFactoryTest extends TestCase
{
    public function testMonsterFactoryCreatesMonsterWithDifficulty(): void
    {
        $difficulty = Difficulty::NORMAL->profileRanges();
        $factory = new MonsterFactory($difficulty);

        $goblin = $factory->createMonster('Goblin');
        
        $this->assertInstanceOf(Goblin::class, $goblin);
        $this->assertTrue($goblin->isAlive());
        $this->assertGreaterThan(0, $goblin->getCurrentHealth());
    }

    public function testMonsterFactoryDiscoverAllMonsters(): void
    {
        $difficulty = Difficulty::NORMAL->profileRanges();
        $factory = new MonsterFactory($difficulty);

        $availableMonsters = $factory->getAvailableMonsters();
        
        $this->assertIsArray($availableMonsters);
        $this->assertContains('Goblin', $availableMonsters);
        $this->assertContains('Troll', $availableMonsters);
        $this->assertContains('Dragon', $availableMonsters);
        $this->assertCount(3, $availableMonsters);
    }

    public function testGetMonstersByPowerLevelReturnsCorrectStructure(): void
    {
        $difficulty = Difficulty::NORMAL->profileRanges();
        $factory = new MonsterFactory($difficulty);

        $monstersByPower = $factory->getMonstersByPowerLevel();
        
        $this->assertIsArray($monstersByPower);
        $this->assertArrayHasKey(1, $monstersByPower); // Goblin power level
        $this->assertArrayHasKey(2, $monstersByPower); // Troll power level
        $this->assertArrayHasKey(3, $monstersByPower); // Dragon power level
    }

    public function testCreateRandomMonsterReturnsDifferentMonstersOverTime(): void
    {
        $difficulty = Difficulty::NORMAL->profileRanges();
        $factory = new MonsterFactory($difficulty);

        $monsterTypes = [];
        // Create 20 monsters to test randomness
        for ($i = 0; $i < 20; $i++) {
            $monster = $factory->createRandomMonster();
            $monsterTypes[$monster->getName()] = true;
        }

        // With 20 iterations, we should see at least 2 different monster types
        $this->assertGreaterThanOrEqual(2, count($monsterTypes));
    }

    public function testEasyDifficultyFavorsWeakerMonsters(): void
    {
        $difficulty = Difficulty::EASY->profileRanges();
        $factory = new MonsterFactory($difficulty);

        $monsterCounts = ['goblin' => 0, 'troll' => 0, 'dragon' => 0];
        
        // Create multiple monsters to test distribution
        for ($i = 0; $i < 50; $i++) {
            $monster = $factory->createRandomMonster();
            $monsterCounts[$monster->getName()]++;
        }

        // On easy difficulty, goblins should be most common
        $this->assertGreaterThan($monsterCounts['dragon'], $monsterCounts['goblin']);
    }

    public function testHardDifficultyAllowsStrongerMonsters(): void
    {
        $difficulty = Difficulty::HARD->profileRanges();
        $factory = new MonsterFactory($difficulty);

        $hasStrongMonster = false;
        
        // Create multiple monsters to verify stronger monsters appear
        for ($i = 0; $i < 50; $i++) {
            $monster = $factory->createRandomMonster();
            if ($monster->getName() === 'dragon' || $monster->getName() === 'troll') {
                $hasStrongMonster = true;
                break;
            }
        }

        $this->assertTrue($hasStrongMonster, 'Hard difficulty should include stronger monsters');
    }
}
