<?php


namespace App\Domain\Models\Json\ResourceCosts;


use App\Domain\Interfaces\SpendsResources;
use App\Domain\Models\MeasurableType;

class FixedResourceCost extends ResourceCost
{
    /**
     * @var int
     */
    protected $amount;

    public function __construct(string $resourceName, int $amount)
    {
        parent::__construct($resourceName);
        $this->amount = $amount;
    }

    public function getDescription(): string
    {
        return $this->amount . ' ' . ucwords($this->resourceName);
    }

    public function getStaminaCost(SpendsResources $spendsResources): int
    {
        if ($this->matchesResourceType(MeasurableType::STAMINA)) {
            return $this->amount;
        }
        return 0;
    }

    public function getManCost(SpendsResources $spendsResources): int
    {
        if ($this->matchesResourceType(MeasurableType::MANA)) {
            return $this->amount;
        }
        return 0;
    }
}
