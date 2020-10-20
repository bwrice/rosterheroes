<?php


namespace App\Facades;


use App\Services\Models\Reference\MaterialService;
use Illuminate\Support\Facades\Facade;

/**
 * Class MaterialFacade
 * @package App\Facades
 *
 * @method static float speedModifierBonus(int $materialID)
 * @method static float baseDamageModifierBonus(int $materialID)
 * @method static float damageMultiplierModifierBonus(int $materialID)
 */
class MaterialFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MaterialService::class;
    }
}
