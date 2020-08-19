<?php


namespace App\Facades;

use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Services\NPCService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * Class NPC
 * @package App\Facades
 *
 * @method static User user
 * @method static string squadName
 * @method static bool isNPC(Squad $squad)
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
