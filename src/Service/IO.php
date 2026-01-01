<?php

namespace CliGame\Service;

class IO
{
    public static function writeLine(string $message): void
    {
        echo $message . PHP_EOL;
    }

    public static function read(): string
    {
        return trim(fgets(STDIN));
    }

    public static function confirm(string $prompt): bool
    {
        static::writeLine($prompt . ' (y/n):');
        $response = static::read();
        return strtolower($response) === 'y';
    }
}