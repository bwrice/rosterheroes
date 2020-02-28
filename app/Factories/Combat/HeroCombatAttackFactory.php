<?php


namespace App\Factories\Combat;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\QueryBuilders\Filters\HeroRaceFilter;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;

class HeroCombatAttackFactory
{
    /** @var CombatAttackFactory */
    protected $combatAttackFactory;

    /** @var CombatAttack */
    protected $combatAttack;

    /** @var HeroFactory */
    protected $heroFactory;

    /** @var ItemFactory */
    protected $itemFactory;

    /** @var ResourceCostsCollection|null */
    protected $resourceCostsCollection;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $heroUuid = $this->getHeroUuid();
        $itemUuid = $this->getItemUuid();
        $resourceCosts = $this->resourceCostsCollection ?: new ResourceCostsCollection();
        $combatAttack = $this->getCombatAttack();
        return new HeroCombatAttack(
            $heroUuid,
            $itemUuid,
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

    public function withCombatAttack(CombatAttack $combatAttack)
    {
        $clone = clone $this;
        $clone->combatAttack = $clone;
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

    protected function getItemUuid()
    {
        $itemFactory = $this->itemFactory ?: ItemFactory::new();
        return $itemFactory->create()->uuid;
    }

    protected function getHeroUuid()
    {
        $heroFactory = $this->heroFactory ?: HeroFactory::new();
        return $heroFactory->create()->uuid;
    }

    public function withResourceCosts(ResourceCostsCollection $resourceCostsCollection)
    {
        $clone = clone $this;
        $clone->resourceCostsCollection = $resourceCostsCollection;
        return $clone;
    }

    /**
     * @return CombatAttack
     */
    protected function getCombatAttack()
    {
        if ($this->combatAttack) {
            return $this->combatAttack;
        }
        $combatAttackFactory = $this->combatAttackFactory ?: CombatAttackFactory::new();
        $combatAttack = $combatAttackFactory->create();
        return $combatAttack;
    }
}
