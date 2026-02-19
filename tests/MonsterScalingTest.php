<?php

use CliGame\Character\Monsters\Goblin;
use CliGame\Character\Monsters\Troll;
use CliGame\Character\Monsters\Dragon;
use CliGame\Enum\Difficulty;
use PHPUnit\Framework\TestCase;

class MonsterScalingTest extends TestCase
{
    public function testGoblinScalesWithDifficulty(): void
    {
        $easyDifficulty = Difficulty::EASY->profileRanges();
        $normalDifficulty = Difficulty::NORMAL->profileRanges();
        $hardDifficulty = Difficulty::HARD->profileRanges();

        $easyGoblin = new Goblin($easyDifficulty);
        $normalGoblin = new Goblin($normalDifficulty);
        $hardGoblin = new Goblin($hardDifficulty);

        // Easy should have lowest stats (easy multiplier is 0.8, normal is 1.0, hard is 1.2)
        $this->assertLessThan($normalGoblin->getMinAttackPower(), $easyGoblin->getMinAttackPower());
        $this->assertLessThan($hardGoblin->getMinAttackPower(), $normalGoblin->getMinAttackPower());

        // Health should also scale (easy has less health than normal, normal less than hard)
        $this->assertLessThan($normalGoblin->getCurrentHealth(), $easyGoblin->getCurrentHealth());
        $this->assertLessThan($hardGoblin->getCurrentHealth(), $normalGoblin->getCurrentHealth());
    }

    public function testTrollScalesWithDifficulty(): void
    {
        $easyDifficulty = Difficulty::EASY->profileRanges();
        $hardDifficulty = Difficulty::HARD->profileRanges();

        $easyTroll = new Troll($easyDifficulty);
        $hardTroll = new Troll($hardDifficulty);

        // Hard troll should be stronger than easy troll
        $this->assertGreaterThan($easyTroll->getMinAttackPower(), $hardTroll->getMinAttackPower());
        $this->assertGreaterThan($easyTroll->getMaxAttackPower(), $hardTroll->getMaxAttackPower());
        $this->assertGreaterThan($easyTroll->getCurrentHealth(), $hardTroll->getCurrentHealth());
    }

    public function testDragonScalesWithDifficulty(): void
    {
        $normalDifficulty = Difficulty::NORMAL->profileRanges();
        $hardDifficulty = Difficulty::HARD->profileRanges();

        $normalDragon = new Dragon($normalDifficulty);
        $hardDragon = new Dragon($hardDifficulty);

        // Hard dragon should be more powerful
        $this->assertGreaterThan($normalDragon->getMinAttackPower(), $hardDragon->getAttackPower());
        $this->assertGreaterThan($normalDragon->getCurrentHealth(), $hardDragon->getCurrentHealth());
    }

    public function testMonsterStatsArePositive(): void
    {
        $difficulty = Difficulty::NORMAL->profileRanges();

        $goblin = new Goblin($difficulty);
        $troll = new Troll($difficulty);
        $dragon = new Dragon($difficulty);

        // All stats should be positive
        $this->assertGreaterThan(0, $goblin->getMinAttackPower());
        $this->assertGreaterThan(0, $goblin->getMaxAttackPower());
        $this->assertGreaterThan(0, $goblin->getCurrentHealth());

        $this->assertGreaterThan(0, $troll->getMinAttackPower());
        $this->assertGreaterThan(0, $troll->getMaxAttackPower());
        $this->assertGreaterThan(0, $troll->getCurrentHealth());

        $this->assertGreaterThan(0, $dragon->getMinAttackPower());
        $this->assertGreaterThan(0, $dragon->getMaxAttackPower());
        $this->assertGreaterThan(0, $dragon->getCurrentHealth());
    }

    public function testMonsterPowerLevelsAreCorrect(): void
    {
        // Test that power levels are in expected order
        $this->assertEquals(1, Goblin::getBasePowerLevel());
        $this->assertEquals(2, Troll::getBasePowerLevel());
        $this->assertEquals(3, Dragon::getBasePowerLevel());

        // Goblin should be weakest
        $this->assertLessThan(Troll::getBasePowerLevel(), Goblin::getBasePowerLevel());
        $this->assertLessThan(Dragon::getBasePowerLevel(), Troll::getBasePowerLevel());
    }

    public function testDragonIsBoss(): void
    {
        $difficulty = Difficulty::NORMAL->profileRanges();
        $dragon = new Dragon($difficulty);

        $this->assertTrue($dragon->isBoss());
        $this->assertFalse($dragon->canPeek());
    }

    public function testGoblinAndTrollAreNotBosses(): void
    {
        $difficulty = Difficulty::NORMAL->profileRanges();
        $goblin = new Goblin($difficulty);
        $troll = new Troll($difficulty);

        $this->assertFalse($goblin->isBoss());
        $this->assertTrue($goblin->canPeek());

        $this->assertFalse($troll->isBoss());
        $this->assertTrue($troll->canPeek());
    }

    public function testMinAttackPowerLessThanMaxAttackPower(): void
    {
        $difficulty = Difficulty::NORMAL->profileRanges();

        $goblin = new Goblin($difficulty);
        $troll = new Troll($difficulty);
        $dragon = new Dragon($difficulty);

        $this->assertLessThanOrEqual($goblin->getMaxAttackPower(), $goblin->getAttackPower());
        $this->assertLessThanOrEqual($troll->getMaxAttackPower(), $troll->getAttackPower());
        $this->assertLessThanOrEqual($dragon->getMaxAttackPower(), $dragon->getAttackPower());
    }
}
