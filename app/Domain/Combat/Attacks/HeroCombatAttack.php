<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use Illuminate\Contracts\Support\Arrayable;

class HeroCombatAttack extends CombatAttack
{
    /**
     * @var string
     */
    protected $heroUuid;
    /**
     * @var string
     */
    protected $itemUuid;
    /**
     * @var CombatAttack
     */
    protected $combatAttack;
    /**
     * @var ResourceCostsCollection
     */
    protected $resourceCosts;

    protected $damagesDealt = [];

    protected $minionKills = 0;

    public function __construct(
        string $heroUuid,
        string $itemUuid,
        string $name,
        string $attackUuid,
        int $damage,
        float $combatSpeed,
        int $tier,
        int $maxTargetsCount,
        CombatPosition $attackerPosition,
        CombatPosition $targetPosition,
        TargetPriority $targetPriority,
        DamageType $damageType,
        ResourceCostsCollection $resourceCosts)
    {
        $this->heroUuid = $heroUuid;
        $this->itemUuid = $itemUuid;
        $this->resourceCosts = $resourceCosts;
        parent::__construct(
            $name,
            $attackUuid,
            $damage,
            $combatSpeed,
            $tier,
            $attackerPosition,
            $targetPosition,
            $targetPriority,
            $damageType,
            $maxTargetsCount
        );
    }

    /**
     * @return string
     */
    public function getHeroUuid()
    {
        return $this->heroUuid;
    }

    /**
     * @return string
     */
    public function getItemUuid()
    {
        return $this->itemUuid;
    }

    /**
     * @return CombatAttack
     */
    public function getCombatAttack(): CombatAttack
    {
        return $this->combatAttack;
    }

    /**
     * @return ResourceCostsCollection
     */
    public function getResourceCosts(): ResourceCostsCollection
    {
        return $this->resourceCosts;
    }

    public function toArray()
    {
        return array_merge([
            'heroUuid' => $this->heroUuid,
            'itemUuid' => $this->itemUuid,
            'resourceCosts' => $this->resourceCosts->toArray()
        ], parent::toArray());
    }

    /**
     * @return array
     */
    public function getDamagesDealt(): array
    {
        return $this->damagesDealt;
    }

    public function addDamageDealt(int $damageDealt)
    {
        $this->damagesDealt[] = $damageDealt;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinionKills(): int
    {
        return $this->minionKills;
    }

    public function addMinionKill()
    {
        $this->minionKills++;
        return $this;
    }
}
