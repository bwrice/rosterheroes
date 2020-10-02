<?php


namespace App\Domain\Combat\Events;


interface CombatEvent
{
    public function moment(): int;

    public function eventStream(): string;
}
