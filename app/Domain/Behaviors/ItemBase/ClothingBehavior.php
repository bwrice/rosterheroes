<?php


namespace App\Domain\Behaviors\ItemBase;


use App\Domain\Behaviors\ItemGroup\ClothingGroup;

abstract class ClothingBehavior extends ItemBaseBehavior
{
    public function __construct(ClothingGroup $clothingGroup)
    {
        parent::__construct($clothingGroup);
    }
}
