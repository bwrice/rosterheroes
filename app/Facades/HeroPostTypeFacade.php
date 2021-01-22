<?php


namespace App\Facades;


use App\Services\Models\Reference\HeroPostTypeService;
use Illuminate\Support\Facades\Facade;

class HeroPostTypeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HeroPostTypeService::class;
    }
}
