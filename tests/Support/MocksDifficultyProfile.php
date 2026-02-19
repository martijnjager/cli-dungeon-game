<?php

use CliGame\Balancer\DifficultyProfile;

trait MocksDifficultyProfile
{
    protected function mockDifficultyProfile(): DifficultyProfile
    {
        return $this->getMockBuilder(DifficultyProfile::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}