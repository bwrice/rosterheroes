<?php


namespace App\Domain\Behaviors\ItemBase;

use App\Domain\Behaviors\ItemGroup\JewelryGroup;

abstract class JewelryBehavior extends ItemBaseBehavior
{
    public function __construct(JewelryGroup $jewelryGroup)
    {
        parent::__construct($jewelryGroup);
    }
}
