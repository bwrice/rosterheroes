<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/6/18
 * Time: 10:07 PM
 */

namespace App\Domain\Behaviors\HeroClass;


class WarriorBehavior extends HeroClassBehavior
{
    const STARTER_SWORD = 'Starter Sword';
    const STARTER_SHIELD = 'Starter Shield';

    protected function getStarterItemBlueprintNames(): array
    {
        return [
            self::STARTER_SWORD,
            self::STARTER_SHIELD
        ];
    }
}