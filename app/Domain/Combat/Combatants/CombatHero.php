<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Interfaces\SpendsResources;
use App\Domain\Models\Hero;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use App\Domain\Models\MeasurableType;
use App\Facades\CombatPositionFacade;

class CombatHero extends AbstractCombatant implements SpendsResources
{
    protected string $sourceUuid;
    protected int $initialStamina, $initialMana, $currentStamina, $currentMana;
    protected array $damagesDealt, $damagesReceived = [];
    protected int $minionKills, $blocks = 0;

    public function __construct(
        string $sourceUuid,
        int $health,
        int $stamina,
        int $mana,
        int $protection,
        float $blockChancePercent,
        int $combatPositionID,
        CombatAttackCollection $combatAttacks)
    {
        $this->sourceUuid = $sourceUuid;
        $this->initialStamina = $this->currentStamina = $stamina;
        $this->initialMana = $this->currentMana = $mana;
        parent::__construct(
            $health,
            $protection,
            $blockChancePercent,
            $combatPositionID,
            $combatAttacks
        );
    }

    /**
     * @return int
     */
    public function getInitialStamina(): int
    {
        return $this->initialStamina;
    }

    /**
     * @param int $initialStamina
     */
    public function setInitialStamina(int $initialStamina): void
    {
        $this->initialStamina = $initialStamina;
    }

    /**
     * @return int
     */
    public function getInitialMana(): int
    {
        return $this->initialMana;
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

    /**
     * @return array
     */
    public function getDamagesReceived(): array
    {
        return $this->damagesReceived;
    }

    /**
     * @param int $damage
     * @return $this
     */
    public function addDamageReceived(int $damage)
    {
        $this->damagesReceived[] = $damage;
        return $this;
    }

    /**
     * @return int
     */
    public function getBlocks(): int
    {
        return $this->blocks;
    }

    public function addBlock()
    {
        $this->blocks++;
        return $this;
    }

    protected function getDPS()
    {
        // TODO
        return 1;
    }

    /**
     * @return string
     */
    public function getSourceUuid(): string
    {
        return $this->sourceUuid;
    }

    public function getCurrentStamina(): int
    {
        return $this->currentStamina;
    }

    public function getCurrentMana(): int
    {
        return $this->currentMana;
    }

    public function setCurrentStamina(int $amount)
    {
        $this->currentStamina = $amount;
    }

    public function setCurrentMana(int $amount)
    {
        $this->currentMana = $amount;
    }

    public function toArray()
    {
        return array_merge([
            'heroUuid' => $this->sourceUuid,
            'initialStamina' => $this->initialStamina,
            'currentStamina' => $this->currentStamina,
            'initialMana' => $this->initialMana,
            'currentMana' => $this->currentMana
        ], parent::toArray());
    }

    public function getReadyAttacks(): CombatAttackCollection
    {
        $combatAttacks = $this->combatAttacks
            ->withinAttackerProximity(CombatPositionFacade::closestProximity($this->allCombatPositions())->getProximity())
            ->ready();

        if ($this->currentStamina <= 0) {
            $combatAttacks = $combatAttacks->filter(function (HeroCombatAttack $heroCombatAttack) {
                $staminaResourceCost = $heroCombatAttack->getResourceCosts()->first(function (ResourceCost $resourceCost) {
                    return $resourceCost->getResourceName() === MeasurableType::STAMINA;
                });
                return is_null($staminaResourceCost);
            });
        }

        if ($this->currentMana <= 0) {
            $combatAttacks = $combatAttacks->filter(function (HeroCombatAttack $heroCombatAttack) {
                $staminaResourceCost = $heroCombatAttack->getResourceCosts()->first(function (ResourceCost $resourceCost) {
                    return $resourceCost->getResourceName() === MeasurableType::MANA;
                });
                return is_null($staminaResourceCost);
            });
        }
        return $combatAttacks;
    }

    public function getHero()
    {
        return Hero::findUuidOrFail($this->getSourceUuid());
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
}
