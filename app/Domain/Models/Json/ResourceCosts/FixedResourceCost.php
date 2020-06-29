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
        return $this->getFixedStaminaCost();
    }

    public function getManCost(SpendsResources $spendsResources): int
    {
        return $this->getFixedManaCost();
    }

    protected function getFixedStaminaCost()
    {
        if ($this->matchesResourceType(MeasurableType::STAMINA)) {
            return $this->amount;
        }
        return 0;
    }

    protected function getFixedManaCost()
    {
        if ($this->matchesResourceType(MeasurableType::MANA)) {
            return $this->amount;
        }
        return 0;
    }

    public function toArray()
    {
        return array_merge([
            'type' => 'fixed',
            'amount' => $this->amount
        ], parent::toArray());
    }

    public function getExpectedStaminaCost(): float
    {
        return $this->getFixedStaminaCost();
    }

    public function getExpectedManaCost(): float
    {
        return $this->getFixedManaCost();
    }
}
