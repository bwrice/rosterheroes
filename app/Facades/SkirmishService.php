<?php


namespace App\Facades;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;

/**
 * Class SkirmishService
 * @package App\Facades
 *
 * @method static Builder query()
 *
 * @see \App\Services\ModelServices\SkirmishService
 */
class SkirmishService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'skirmish-service';
    }
}
