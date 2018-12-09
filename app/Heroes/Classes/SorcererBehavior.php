<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/6/18
 * Time: 10:08 PM
 */

namespace App\Heroes\Classes;


class SorcererBehavior extends HeroClassBehavior
{
    const STARTER_STAFF = 'Starter Staff';

    protected function getStarterItemBlueprintNames(): array
    {
        return [
            self::STARTER_STAFF
        ];
    }
}