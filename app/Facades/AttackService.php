<?php


namespace App\Facades;


use App\Domain\Collections\AttackCollection;
use Illuminate\Support\Facades\Facade;

/**
 * Class AttackService
 * @package App\Facades
 *
 * @method static AttackCollection all()
 *
 * @see \App\Services\ModelServices\AttackService
 */
class AttackService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'attack-service';
    }
}
