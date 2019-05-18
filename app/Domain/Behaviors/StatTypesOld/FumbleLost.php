<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/11/19
 * Time: 2:27 PM
 */

namespace App\Domain\Behaviors\StatTypesOld;


use App\Domain\Enums\StatTypes\StatType;

class FumbleLost extends StatType
{
    public const NAME = 'fumble-lost';
}