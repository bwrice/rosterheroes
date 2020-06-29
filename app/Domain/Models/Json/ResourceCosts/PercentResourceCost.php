<?php


namespace App\Domain\Models\Json\ResourceCosts;


use App\Domain\Interfaces\SpendsResources;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use App\Domain\Models\MeasurableType;

class PercentResourceCost extends ResourceCost
{
    /**
     * @var float
     */
    private $percent;

    public function __construct(string $resourceName, float $percent)
    {
        parent::__construct($resourceName);
        $this->percent = $percent;
    }

    public function getDescription(): string
    {
        return $this->percent . '% of available ' . ucwords($this->resourceName);
    }

    public function getStaminaCost(SpendsResources $spendsResources): int
    {
        if ($this->matchesResourceType(MeasurableType::STAMINA)) {
            $currentStamina = $spendsResources->getCurrentStamina();
            return (int) max(0, floor($currentStamina * ($this->percent/100)));
        }
        return 0;
    }

    public function getManCost(SpendsResources $spendsResources): int
    {
        if ($this->matchesResourceType(MeasurableType::MANA)) {
            $currentMana = $spendsResources->getCurrentMana();
            return (int) max(0, floor($currentMana * ($this->percent/100)));
        }
        return 0;
    }

    public function toArray()
    {
        return array_merge([
            'type' => 'percent',
            'percent' => $this->percent
        ], parent::toArray());
    }

    public function getExpectedStaminaCost(): float
    {
        return 800 * ($this->percent/100);
    }

    public function getExpectedManaCost(): float
    {
        return 250 * ($this->percent/100);
    }
}
