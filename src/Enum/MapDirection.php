<?php

namespace CliGame\Enum;

enum MapDirection: string
{
    case NORTH = 'north';
    case SOUTH = 'south';
    case EAST = 'east';
    case WEST = 'west';

    public static function toArray(): array
    {
        return [
            self::NORTH->value,
            self::SOUTH->value,
            self::EAST->value,
            self::WEST->value,
        ];
    }

    public static function fromString(string $value): MapDirection
    {
        return match (strtolower($value)) {
            'north' => self::NORTH,
            'south' => self::SOUTH,
            'east' => self::EAST,
            'west' => self::WEST,
            default => null,
        };
    }

    public static function deltaDirection(string $direction): ?array
    {
        return match (strtolower($direction)) {
            'north' => [0, -1],
            'south' => [0, 1],
            'east' => [1, 0],
            'west' => [-1, 0],
            default => null,
        };
    }
}