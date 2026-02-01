<?php

namespace CliGame\Command;

class CommandResult
{
    private bool $shouldExit;
    private ?string $output;

    public function __construct(bool $shouldExit = false, ?string $output = null)
    {
        $this->shouldExit = $shouldExit;
        $this->output = $output;
    }

    public function shouldExit(): bool
    {
        return $this->shouldExit;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public static function quit(string $message = 'Goodbye!'): self
    {
        return new self(true, $message);
    }

    public static function continue(string $message = ''): self
    {
        return new self(false, $message);
    }
}
