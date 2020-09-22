<?php


namespace App\Facades;


use App\Domain\Models\Squad;
use Illuminate\Support\Facades\Facade;

/**
 * Class SquadService
 * @package App\Facades
 *
 * @method static bool combatReady(Squad $squad)
 * @method static bool inCreationState(Squad $squad)
 *
 * @see \App\Services\Models\SquadService
 */
class SquadFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'squad-service';
    }
}
