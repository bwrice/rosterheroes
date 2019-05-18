<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/11/19
 * Time: 11:15 AM
 */

namespace App\Domain\Behaviors\StatTypesOld;


use App\Domain\Enums\StatTypes\StatType;

class ReceivingTouchdown extends StatType
{
    public const NAME = 'receiving-touchdown';
}