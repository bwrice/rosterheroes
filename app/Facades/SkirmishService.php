<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class SkirmishService
 * @package App\Facades
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
