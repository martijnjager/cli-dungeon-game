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
}
