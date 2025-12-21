<?php

namespace CliGame;

class Location
{
    private int $x;
    private int $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function equals(Location $otherLocation): bool
    {
        return $this->x === $otherLocation->getX() && $this->y === $otherLocation->getY();
    }

    public function printCoordinates()
    {
        return "({$this->x}, {$this->y})";
    }
}