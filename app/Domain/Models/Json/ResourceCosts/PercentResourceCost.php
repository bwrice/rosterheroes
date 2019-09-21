<?php


namespace App\Domain\Models\Json\ResourceCosts;


use App\Domain\Models\Json\ResourceCosts\ResourceCost;

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
}
