<?php


namespace App\Factories\Combat;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\QueryBuilders\Filters\HeroRaceFilter;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;

class HeroCombatAttackFactory
{
    /** @var CombatAttackFactory */
    protected $combatAttackFactory;

    /** @var HeroFactory */
    protected $heroFactory;

    /** @var ItemFactory */
    protected $itemFactory;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $heroID = $this->getHeroID();
        $itemID = $this->getItemID();
        $resourceCosts = new ResourceCostsCollection();
        $combatAttackFactory = $this->combatAttackFactory ?: CombatAttackFactory::new();
        $combatAttack = $combatAttackFactory->create();
        return new HeroCombatAttack(
            $heroID,
            $itemID,
            $combatAttack,
            $resourceCosts
        );
    }

    public function withCombatAttackFactory(CombatAttackFactory $combatAttackFactory)
    {
        $clone = clone $this;
        $clone->combatAttackFactory = $combatAttackFactory;
        return $clone;
    }

    public function fromItemFactory(ItemFactory $itemFactory)
    {
        $clone = clone $this;
        $clone->itemFactory = $itemFactory;
        return $clone;
    }

    public function fromHeroFactory(HeroFactory $heroFactory)
    {
        $clone = clone $this;
        $clone->heroFactory = $heroFactory;
        return $clone;
    }

    protected function getItemID()
    {
        $itemFactory = $this->itemFactory ?: ItemFactory::new();
        return $itemFactory->create()->id;
    }

    protected function getHeroID()
    {
        $heroFactory = $this->heroFactory ?: HeroFactory::new();
        return $heroFactory->create()->id;
    }
}
