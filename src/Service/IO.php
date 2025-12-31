<?php

namespace CliGame\Service;

class IO
{
    public static function writeLine(string $message): void
    {
        echo $message . PHP_EOL;
    }

    public static function read(string $prompt): string
    {
        echo $prompt . ' ';
        return trim(fgets(STDIN));
    }

    public static function confirm(string $prompt): bool
    {
        $response = static::read($prompt . ' (y/n):');
        return strtolower($response) === 'y';
    }
}