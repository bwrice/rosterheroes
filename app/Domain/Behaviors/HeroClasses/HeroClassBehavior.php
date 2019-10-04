<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/6/18
 * Time: 8:12 PM
 */

namespace App\Domain\Behaviors\HeroClasses;


use App\Domain\Behaviors\MeasurableTypes\Attributes\AttributeBehavior;
use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\QualityBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\ResourceBehavior;
use App\Domain\Interfaces\MeasurableCalculator;
use App\Domain\Interfaces\MeasurableOperator;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Collections\ItemBlueprintCollection;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;

abstract class HeroClassBehavior
{
    protected $primaryMeasurableTypes = [];

    protected $secondaryMeasurableTypes = [];

    /**
     * @var MeasurableCalculator
     */
    private $measurableCalculator;
    /**
     * @var MeasurableOperator
     */
    private $measurableOperator;

    public function __construct(MeasurableCalculator $measurableCalculator, MeasurableOperator $measurableOperator)
    {
        $this->measurableCalculator = $measurableCalculator;
        $this->measurableOperator = $measurableOperator;
    }

    /**
     * @return array
     */
    abstract protected function getStarterItemBlueprintNames(): array;

    abstract public function getStartingCombatPosition(): CombatPosition;

    abstract public function getIconSVG(): string;

    /**
     * @return ItemBlueprintCollection
     */
    public function getStartItemBlueprints()
    {
        /** @var ItemBlueprintCollection $collection */
        $collection = ItemBlueprint::query()->whereIn('item_name', $this->getStarterItemBlueprintNames())->get();
        return $collection;
    }

    /**
     * @param Measurable $measurable
     * @param int $amount
     * @return int
     */
    public function costToRaiseMeasurable(Measurable $measurable, int $amount = 1): int
    {
        return $this->measurableCalculator->getCostToRaise($measurable, $this->measurableOperator, $amount);
    }

    public function spentOnRaisingMeasurable(Measurable $measurable): int
    {
        return $this->measurableCalculator->spentOnRaising($measurable, $this->measurableOperator);
    }

    /**
     * @param Measurable $measurable
     * @return int
     */
    public function getCurrentMeasurableAmount(Measurable $measurable): int
    {
        return $this->measurableCalculator->getCurrentAmount($measurable, $this->measurableOperator);
    }

    public function getMeasurableStartingAmount(MeasurableTypeBehavior $measurableTypeBehavior): int
    {
        $baseAmount = $this->getMeasurableBaseAmount($measurableTypeBehavior->getTypeName());
        return $baseAmount + $this->getMeasurableBonusAmount($measurableTypeBehavior->getTypeName(), $measurableTypeBehavior->getGroupName());
    }

    /**
     * @param string $measurableTypeName
     * @return int
     */
    protected function getMeasurableBaseAmount(string $measurableTypeName): int
    {
        switch ($measurableTypeName) {
            case MeasurableType::STRENGTH:
            case MeasurableType::VALOR:
            case MeasurableType::AGILITY:
            case MeasurableType::FOCUS:
            case MeasurableType::APTITUDE:
            case MeasurableType::INTELLIGENCE:
                return 20;
            case MeasurableType::BALANCE:
            case MeasurableType::HONOR:
            case MeasurableType::PASSION:
            case MeasurableType::PRESTIGE:
            case MeasurableType::WRATH:
                return 100;
            case MeasurableType::MANA:
            case MeasurableType::STAMINA:
                return 200;
            case MeasurableType::HEALTH:
                return 400;
        }
        throw new \InvalidArgumentException("Unknown measurable-type name: " . $measurableTypeName);
    }

    protected function getMeasurableBonusAmount(string $measurableTypeName, string $measurableGroupName): int
    {
        $groupMultiplier = $this->getMeasurableAmountGroupMultiplier($measurableGroupName);
        return (int) $groupMultiplier * $this->getMeasurableAmountOrdinalBonus($measurableTypeName);
    }

    protected function getMeasurableAmountGroupMultiplier(string $measurableGroupName): int
    {
        switch ($measurableGroupName) {
            case AttributeBehavior::GROUP_NAME:
                return 1;
            case QualityBehavior::GROUP_NAME:
                return 2;
            case ResourceBehavior::GROUP_NAME:
                return 4;
        }
        throw new \InvalidArgumentException("Unknown measurable-group name: " . $measurableGroupName);
    }

    protected function getMeasurableAmountOrdinalBonus(string $measurableTypeName): int
    {
        if ($this->measurableTypeIsPrimary($measurableTypeName)) {
            return 40;
        } else if($this->measurableTypeIsSecondary($measurableTypeName)) {
            return 20;
        }
        return 0;
    }

    protected function measurableTypeIsPrimary(string $measurableTypeName)
    {
        return in_array($measurableTypeName, $this->primaryMeasurableTypes);
    }

    protected function measurableTypeIsSecondary(string $measurableTypeName)
    {
        return in_array($measurableTypeName, $this->secondaryMeasurableTypes);
    }
}
