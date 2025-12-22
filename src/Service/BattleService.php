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

            $log[] = "New Round! " . ucfirst($startingTurn) . " starts first.";

            switch ($startingTurn) {
                case 'player':
                    $log[] = $this->playerTurn();

                    if ($this->monster->isAlive()) {
                        $log[] = $this->monsterTurn();
                    }
                    break;
                case 'monster':
                    $log[] = $this->monsterTurn();

                    if ($this->player->isAlive()) {
                        $log[] = $this->playerTurn();
                    }
                    break;
            }
        }

        return new Result($log, $this->player->isAlive(), $this->monster->isAlive());
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
     * @return string Log of the action taken
     */
    private function playerTurn(): string
    {
        // Player's attack logic
        $damage = $this->player->getAttackPower();
        $this->monster->takeDamage($damage);
        return $this->player->getName() . " attacks " . $this->monster->getName() . " for " . $damage . " damage!";
    }

    /**
     * Monster's turn logic
     * @return string Log of the action taken
     */
    private function monsterTurn(): string
    {
        // Monster's attack logic
        $damage = $this->monster->getAttackPower();
        $this->player->takeDamage($damage);
        return $this->monster->getName() . " attacks " . $this->player->getName() . " for " . $damage . " damage!";
    }
}