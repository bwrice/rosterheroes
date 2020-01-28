<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class SquadService
 * @package App\Facades
 *
 * @see \App\Services\ModelServices\SquadService
 */
class SquadService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'squad-service';
    }
}
