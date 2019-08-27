<?php


namespace App\Domain\Behaviors\ItemBase;


use App\Domain\Behaviors\ItemGroup\ArmorGroup;

abstract class ArmorBehavior extends ItemBaseBehavior
{
    public function __construct(ArmorGroup $armorGroup)
    {
        parent::__construct($armorGroup);
    }
}
