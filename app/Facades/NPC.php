<?php


namespace App\Facades;

use App\Services\NPCService;
use Illuminate\Support\Facades\Facade;

/**
 * Class NPC
 * @package App\Facades
 *
 * @see NPCService
 */
class NPC extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'npc';
    }
}
