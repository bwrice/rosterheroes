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
use App\Domain\Models\CombatPosition;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Collections\ItemBlueprintCollection;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;

abstract class HeroClassBehavior
{
    /** @var array  */
    protected $primaryMeasurableTypes = [];
    /** @var array  */
    protected $secondaryMeasurableTypes = [];
    /** @var array  */
    protected $starterItemBlueprintNames = [];

    /**
     * @return array
     */
    protected function getStarterItemBlueprintNames()
    {
        return $this->starterItemBlueprintNames;
    }

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

    public function spentOnRaisingMeasurable(MeasurableTypeBehavior $measurableTypeBehavior, int $amountRaised): int
    {
        if ($amountRaised < 1) {
            return 0;
        }
        return $this->sumCostToRaiseMeasurable($measurableTypeBehavior, 1, $amountRaised);
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
        $groupMultiplier = 1 / $this->getMeasurableGroupWeight($measurableGroupName);
        return (int) ($groupMultiplier * $this->getMeasurableAmountOrdinalBonus($measurableTypeName));
    }

    protected function getMeasurableGroupWeight(string $measurableGroupName): float
    {
        switch ($measurableGroupName) {
            case AttributeBehavior::GROUP_NAME:
                return 1;
            case QualityBehavior::GROUP_NAME:
                return 1/2;
            case ResourceBehavior::GROUP_NAME:
                return 1/8;
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

    /**
     * @param MeasurableTypeBehavior $measurableTypeBehavior
     * @param int $amountAlreadyRaised
     * @param int $amountToRaise
     * @return int
     */
    public function costToRaiseMeasurable(MeasurableTypeBehavior $measurableTypeBehavior, int $amountAlreadyRaised, int $amountToRaise = 1): int
    {
        $startRange = $amountAlreadyRaised + 1;
        $endRange = $amountAlreadyRaised + $amountToRaise;
        return $this->sumCostToRaiseMeasurable($measurableTypeBehavior, $startRange, $endRange);
    }

    protected function getCostToRaiseMeasurableBaseAmount(string $measurableTypeName, string $measurableGroupName)
    {
        $groupWeight = $this->getMeasurableGroupWeight($measurableGroupName);

        if ($this->measurableTypeIsPrimary($measurableTypeName)) {
            return 40 * $groupWeight;
        } elseif ($this->measurableTypeIsSecondary($measurableTypeName)) {
            return 60 * $groupWeight;
        }
        return 75 * $groupWeight;
    }

    public function getCostToRaiseMeasurableExponent(string $measurableTypeName): float
    {
        if ($this->measurableTypeIsPrimary($measurableTypeName)) {
            return 1.85;
        } elseif ($this->measurableTypeIsSecondary($measurableTypeName)) {
            return 2;
        }
        return 2.15;
    }

    protected function sumCostToRaiseMeasurable(MeasurableTypeBehavior $measurableTypeBehavior, int $raisedAmountStart, int $raisedAmountEnd)
    {
        $baseAmount = $this->getCostToRaiseMeasurableBaseAmount($measurableTypeBehavior->getTypeName(), $measurableTypeBehavior->getGroupName());
        $exponent = $this->getCostToRaiseMeasurableExponent($measurableTypeBehavior->getTypeName());
        $totalCost = 0;
        foreach (range($raisedAmountStart, $raisedAmountEnd) as $amountRaised) {
            $totalCost += $this->calculateSingleCostToRaiseMeasurable($amountRaised, $baseAmount, $exponent);
        }
        return $totalCost;
    }


    protected function calculateSingleCostToRaiseMeasurable(int $newAmountRaised, float $base, float $exponent): int
    {
        /*
         * Subtract 1 from new amount raised so that a measurable not raised
         * will only cost the base amount for the initial raise
         */
        return (int) round($base + (($base/4) * ($newAmountRaised - 1) ** $exponent));
    }
}
