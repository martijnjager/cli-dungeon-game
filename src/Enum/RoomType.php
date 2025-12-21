<?php

namespace CliGame\Enum;

enum RoomType: string
{
    case EMPTY = 'empty';
    case ENEMY = 'enemy';
    case TREASURE = 'treasure';
    case UNKNOWN = 'unknown';
}
