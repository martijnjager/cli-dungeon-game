<?php

use CliGame\Util\JsonFileLoader;
use PHPUnit\Framework\TestCase;

class JsonFileLoaderTest extends TestCase
{
    /** @var string[] */
    private array $temporaryFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->temporaryFiles as $filePath) {
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }
    }

    public function testLoadJsonFileAsObjectReturnsDecodedObject(): void
    {
        $filePath = $this->createTemporaryFile('{"name":"Dungeon","level":3,"hardcore":false}');
        $loader = new JsonFileLoader();

        $decoded = $loader->loadJsonFileAsObject($filePath);

        $this->assertIsObject($decoded);
        $this->assertSame('Dungeon', $decoded->name);
        $this->assertSame(3, $decoded->level);
        $this->assertFalse($decoded->hardcore);
    }

    public function testLoadJsonFileAsObjectThrowsWhenFileDoesNotExist(): void
    {
        $filePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'json-loader-missing-' . uniqid('', true) . '.json';
        $loader = new JsonFileLoader();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('JSON file not found at %s', $filePath));

        $loader->loadJsonFileAsObject($filePath);
    }

    public function testLoadJsonFileAsObjectThrowsOnInvalidJson(): void
    {
        $filePath = $this->createTemporaryFile('{"name": "Dungeon", }');
        $loader = new JsonFileLoader();

        try {
            $loader->loadJsonFileAsObject($filePath);
            $this->fail('Expected RuntimeException was not thrown.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString(sprintf('Invalid JSON in file at %s:', $filePath), $exception->getMessage());
            $this->assertInstanceOf(JsonException::class, $exception->getPrevious());
        }
    }

    public function testLoadJsonFileAsObjectThrowsWhenJsonRootIsNotObject(): void
    {
        $filePath = $this->createTemporaryFile('["goblin", "troll"]');
        $loader = new JsonFileLoader();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('JSON file at %s must contain a JSON object.', $filePath));

        $loader->loadJsonFileAsObject($filePath);
    }

    private function createTemporaryFile(string $contents): string
    {
        $filePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'json-loader-' . uniqid('', true) . '.json';
        file_put_contents($filePath, $contents);
        $this->temporaryFiles[] = $filePath;

        return $filePath;
    }
}
