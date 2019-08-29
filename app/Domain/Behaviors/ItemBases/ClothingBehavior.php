<?php


namespace App\Domain\Behaviors\ItemBases;


use App\Domain\Behaviors\ItemGroup\ClothingGroup;

abstract class ClothingBehavior extends ItemBaseBehavior
{
    public function __construct(ClothingGroup $clothingGroup)
    {
        parent::__construct($clothingGroup);
    }
}
