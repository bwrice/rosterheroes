<?php


namespace App\Facades;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;

/**
 * Class SideQuestService
 * @package App\Facades
 *
 * @method static Builder query()
 *
 * @see \App\Services\ModelServices\SideQuestService
 */
class SideQuestService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'side-quest-service';
    }
}
