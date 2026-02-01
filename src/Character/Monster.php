<?php

namespace CliGame\Character;

use CliGame\Balancer\DifficultyProfile;
use CliGame\Character\Weapon\DefaultWeapon;
use CliGame\Enum\MonsterType;
use CliGame\Trait\AttackPower;
use CliGame\Trait\DamageBasedHealth;

class Monster
{
    use AttackPower;
    use DamageBasedHealth;

    protected MonsterType $type;
    protected bool $isBoss = false;

    protected bool $canPeek = true;

    protected Weapon $activeWeapon;

    public function __construct(DifficultyProfile $difficulty)
    {
        $this->activeWeapon = new DefaultWeapon("Default Weapon", 0, "A basic weapon with no special abilities.");
        $this->initializeHealth($difficulty, $this->minAttackPower, $this->maxAttackPower);
    }

    public function getName(): string
    {
        return $this->type->value;
    }

    public function getType(): MonsterType
    {
        return $this->type;
    }

    public function isBoss(): bool
    {
        return $this->isBoss;
    }

    public function canPeek(): bool
    {
        return $this->canPeek;
    }

    public function caughtPlayerPeeking(): bool
    {
        // 50% chance of catching the player if peeking is allowed
        // otherwise always catches the player
        if ($this->canPeek) {
            return rand(0, 1) === 1;
        }
        
        return !$this->canPeek;
    }

    public function getActiveWeapon(): Weapon
    {
        return $this->activeWeapon;
    }

    public function chooseAttackOption(): AttackOption
    {
        $options = $this->activeWeapon->getAttackOptions();
        return $options[array_rand($options)];
    }
}
