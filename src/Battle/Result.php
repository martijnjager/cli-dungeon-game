<?php

namespace CliGame\Battle;

class Result
{
    private bool $playerAlive;
    private bool $monsterAlive;

    public function __construct(bool $playerAlive, bool $monsterAlive)
    {
        $this->playerAlive = $playerAlive;
        $this->monsterAlive = $monsterAlive;
    }

    public function playerSurvived(): bool
    {
        return $this->playerAlive;
    }

    public function monsterSurvived(): bool
    {
        return $this->monsterAlive;
    }
}