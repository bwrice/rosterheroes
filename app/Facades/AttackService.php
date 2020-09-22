<?php


namespace App\Facades;


use App\Domain\Collections\AttackCollection;
use App\Domain\QueryBuilders\AttackQueryBuilder;
use Illuminate\Support\Facades\Facade;

/**
 * Class AttackService
 * @package App\Facades
 *
 * @method static AttackCollection all()
 * @method static AttackQueryBuilder query()
 *
 * @see \App\Services\Models\AttackService
 */
class AttackService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'attack-service';
    }
}
