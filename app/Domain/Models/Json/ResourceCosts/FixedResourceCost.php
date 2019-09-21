<?php


namespace App\Domain\Models\Json\ResourceCosts;


use App\Domain\Models\Json\ResourceCosts\ResourceCost;

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
        return $this->amount . ' ' . $this->resourceName;
    }
}
