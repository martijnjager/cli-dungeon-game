<?php

namespace CliGame;

use CliGame\Enum\RoomType;
use CliGame\Character\Monster;
use CliGame\Character\Player;

class Room
{
    protected RoomType $type;

    private Location $location;

    private bool $isDiscovered = false;

    private ?Monster $monster;

    private int $treasure = 0;

    public function __construct(RoomType $type, Location $location, ?Monster $monster = null, int $treasure = 0)
    {
        $this->type = $type;
        $this->location = $location;
        $this->monster = $monster;
        $this->treasure = $treasure;
    }
    
    public function getType(): RoomType
    {
        if ($this->isDiscovered) {
            return $this->type;
        }

        return RoomType::UNKNOWN;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function isDiscovered(): bool
    {
        return $this->isDiscovered;
    }

    public function discover(Player $player): void
    {
        $player->move($this->location);
        $this->isDiscovered = true;

        if ($this->hasTreasure()) {
            $treasureAmount = $this->collectTreasure();
            $player->collectTreasure($treasureAmount);
        }
    }

    public function peek(): string
    {
        if (!$this->isDiscovered && !empty($this->monster) && !$this->monster->canPeek()) {
            // Monster caught you peeking, engage in life or death battle
            return 'engage';
        }

        return $this->type->value;
    }

    public function hasMonster(): bool
    {
        return $this->monster !== null && $this->monster->isAlive();
    }

    /**
     * @return Monster|null
     */
    public function getMonster(): ?Monster
    {
        return $this->monster;
    }

    /**
     * @return bool
     */
    public function hasTreasure(): bool
    {
        return $this->treasure > 0;
    }

    /**
     * @return int
     */
    public function getTreasureAmount(): int
    {
        return $this->treasure;
    }

    /**
     * @return int
     */
    public function collectTreasure(): int
    {
        $collected = $this->treasure;
        $this->treasure = 0;

        return $collected;
    }
}
