<?php


namespace App\Factories\Combat;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\QueryBuilders\Filters\HeroRaceFilter;
use App\Factories\Models\AttackFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;

class HeroCombatAttackFactory extends AbstractCombatAttackFactory
{
    protected $heroUuid;

    protected $itemUuid;

    /** @var AbstractCombatAttackFactory */
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
        $name = 'Test_Hero_Combat_Attack ' . rand(1, 99999);
        return new HeroCombatAttack(
            $heroUuid,
            $itemUuid,
            $name,
            $this->getAttackUuid(),
            $this->getDamage(),
            $this->getCombatSpeed(),
            $this->getGrade(),
            $this->getMaxTargetsCount(),
            $this->getAttackerPosition(),
            $this->getTargetPosition(),
            $this->getTargetPriority(),
            $this->getDamageType(),
            $this->resourceCostsCollection ?: new ResourceCostsCollection()
        );
    }

    public function withCombatAttackFactory(AbstractCombatAttackFactory $combatAttackFactory)
    {
        $clone = clone $this;
        $clone->combatAttackFactory = $combatAttackFactory;
        return $clone;
    }

    public function withCombatAttack(CombatAttack $combatAttack)
    {
        $clone = clone $this;
        $clone->combatAttack = $combatAttack;
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

    public function forHero(string $heroUuid)
    {
        $clone = clone $this;
        $clone->heroUuid = $heroUuid;
        return $clone;
    }

    public function forItem(string $itemUuid)
    {
        $clone = clone $this;
        $clone->itemUuid = $itemUuid;
        return $clone;
    }

    protected function getItemUuid()
    {
        if ($this->itemUuid) {
            return $this->itemUuid;
        }
        $itemFactory = $this->itemFactory ?: ItemFactory::new();
        return $itemFactory->create()->uuid;
    }

    protected function getHeroUuid()
    {
        if ($this->heroUuid) {
            return $this->heroUuid;
        }
        $heroFactory = $this->heroFactory ?: HeroFactory::new();
        return $heroFactory->create()->uuid;
    }

    public function withResourceCosts(ResourceCostsCollection $resourceCostsCollection)
    {
        $clone = clone $this;
        $clone->resourceCostsCollection = $resourceCostsCollection;
        return $clone;
    }
}
