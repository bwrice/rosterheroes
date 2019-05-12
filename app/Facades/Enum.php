<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/11/19
 * Time: 12:31 PM
 */

namespace App\Facades;


use App\Domain\Enums\Sports\Sport;
use App\Domain\Enums\StatTypes\StatType;
use Illuminate\Support\Facades\Facade;

/**
 * @method static StatType statType($key)
 * @method static Sport sport($key)
 */
class Enum extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'enum';
    }
}