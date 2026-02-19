<?php

namespace CliGame\Util;

use Symfony\Component\Filesystem\Filesystem;

class JsonFileLoader
{
    public function __construct(private readonly Filesystem $filesystem = new Filesystem())
    {
    }

    public function loadJsonFileAsObject(string $path): object
    {
        if (!$this->filesystem->exists($path)) {
            throw new \RuntimeException(sprintf('JSON file not found at %s', $path));
        }

        $contents = $this->filesystem->readFile($path);

        try {
            $decoded = json_decode($contents, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new \RuntimeException(
                sprintf('Invalid JSON in file at %s: %s', $path, $exception->getMessage()),
                0,
                $exception
            );
        }

        if (!is_object($decoded)) {
            throw new \RuntimeException(sprintf('JSON file at %s must contain a JSON object.', $path));
        }

        return $decoded;
    }
}
