<?php

namespace CliGame\Battle;

class Result
{
    private array $log;
    private bool $playerAlive;
    private bool $monsterAlive;

    public function __construct(array $log, bool $playerAlive, bool $monsterAlive)
    {
        $this->log = $log;
        $this->playerAlive = $playerAlive;
        $this->monsterAlive = $monsterAlive;
    }

    public function getLog(): array
    {
        return $this->log;
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