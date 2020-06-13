<?php


namespace App\Facades;


use App\Domain\Models\Squad;
use Illuminate\Support\Facades\Facade;

/**
 * Class SquadService
 * @package App\Facades
 *
 * @method static bool combatReady(Squad $squad)
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
