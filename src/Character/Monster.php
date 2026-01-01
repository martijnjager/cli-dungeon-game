<?php

namespace CliGame\Character;

use CliGame\Character\Weapon\DefaultWeapon;
use CliGame\Enum\MonsterType;
use CliGame\Trait\AttackPower;

class Monster
{
    use AttackPower;

    protected MonsterType $type;
    protected int $health;

    protected bool $isBoss = false;

    protected bool $canPeek = true;

    protected Weapon $activeWeapon;

    public function __construct()
    {
        $this->activeWeapon = new DefaultWeapon("Default Weapon", 0, "A basic weapon with no special abilities.");
    }

    public function getName(): string
    {
        return $this->type->value;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function takeDamage(int $damage): void
    {
        $this->health -= $damage;
        if ($this->health < 0) {
            $this->health = 0;
        }
    }

    public function isAlive(): bool
    {
        return $this->health > 0;
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
