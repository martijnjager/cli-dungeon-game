<?php

namespace CliGame\Enum;

use CliGame\Balancer\DifficultyProfile;
use CliGame\Util\JsonFileLoader;

enum Difficulty: string
{
    case EASY = 'easy';
    case NORMAL = 'normal';
    case HARD = 'hard';

    public static function fromString(string $value): Difficulty
    {
        return match (strtolower($value)) {
            'easy' => self::EASY,
            'hard' => self::HARD,
            default => self::NORMAL,
        };
    }

    public function profileRanges(): DifficultyProfile
    {
        $config = $this->loadConfig();

        $namedArgs = [];
        $ref = new \ReflectionClass(DifficultyProfile::class);
        $ctor = $ref->getConstructor();
        if ($ctor) {
            foreach ($ctor->getParameters() as $param) {
                $name = $param->getName();
                if (property_exists($config, $name)) {
                    $namedArgs[$name] = $config->$name;
                }
            }
        }

        return new DifficultyProfile(...$namedArgs);
    }

    private function loadConfig(): object
    {
        $configPath = dirname(__DIR__, 2) . '/config/difficulty/' . $this->value . '.json';
        $loader = new JsonFileLoader();
        return $loader->loadJsonFileAsObject($configPath);
    }
}