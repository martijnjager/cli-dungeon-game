<?php

namespace CliGame\Service;

use CliGame\Battle\Result;
use CliGame\Character\Player;
use CliGame\Character\Monster;

class BattleService
{
    protected Player $player;
    protected Monster $monster;

    /**
     * Start the battle between player and monster
     * @return Result Battle result including logs and status
     */
    public function startBattle(Player $player, Monster $monster): Result
    {
        $this->player = $player;
        $this->monster = $monster;
        $log = [];

        while ($this->player->isAlive() && $this->monster->isAlive()) {

            $startingTurn = $this->calculateStartingTurn();

            switch ($startingTurn) {
                case 'player':
                    $this->playerTurn();

                    if ($this->monster->isAlive()) {
                        $this->monsterTurn();
                    }
                    break;
                case 'monster':
                    $this->monsterTurn();

                    if ($this->player->isAlive()) {
                        $this->playerTurn();
                    }
                    break;
            }
        }

        return new Result($this->player->isAlive(), $this->monster->isAlive());
    }

    /**
     * Determine who starts first based on initiative rolls
     * @return string 'player' or 'monster'
     */
    private function calculateStartingTurn(): string
    {
        $initiativePlayer = $this->rollInitiative($this->player->getAttackPower(), $this->player->getAgility(), $this->player->getCurrentHealth());
        $initiativeMonster = $this->rollInitiative($this->monster->getAttackPower(), $this->monster->getAgility(), $this->monster->getHealth());
        // Determine who starts first
        return $initiativePlayer >= $initiativeMonster ? 'player' : 'monster';
    }

    /**
     * Roll initiative based on attack power and agility
     * @param int $attackPower
     * @param int $agility
     * @return int
     */
    private function rollInitiative(int $attackPower, int $agility, int $health): int
    {
        return $attackPower * 2 + $agility + $health + random_int(0, 5);
    }

    /**
     * Player's turn logic
     * @return void
     */
    private function playerTurn()
    {
        IO::writeLine("Choose your attack:");
        $this->player->getActiveWeapon()->printAttackOptions();
        $choice = fgets(STDIN);
        $options = $this->player->getActiveWeapon()->getAttackOptions();
        $selectedOption = $options[intval(trim($choice))] ?? null;

        if ($selectedOption === null) {
            IO::writeLine("Invalid attack option selected. Turn skipped.");
            return;
        }

        if (!$this->calculateReceivesHit($selectedOption->hitChance)) {
            IO::writeLine($this->player->getName() . " tried to use " . $selectedOption->name . " but missed!");
            return;
        }

        // Player's attack logic
        $damage = $selectedOption->damage();
        $this->handleDealDamage($this->player, $this->monster, $damage);
    }

    /**
     * Monster's turn logic
     * @return void
     */
    private function monsterTurn()
    {
        // Monster's attack logic
        $attackOption = $this->monster->chooseAttackOption();

        if (!$this->calculateReceivesHit($attackOption->hitChance)) {
            IO::writeLine($this->monster->getName() . " tried to use " . $attackOption->name . " but missed!");
            return;
        }

        $damage = $attackOption->damage();
        $this->handleDealDamage($this->monster, $this->player, $damage);
    }

    private function calculateReceivesHit(int $hitChance): bool
    {
        $roll = rand(1, 100);
        return $roll <= $hitChance;
    }

    private function handleDealDamage($target, $defender, $damage): void
    {
        $defender->takeDamage($damage);
        IO::writeLine("{$target->getName()} deals {$damage} damage to {$defender->getName()}!");
    }
}