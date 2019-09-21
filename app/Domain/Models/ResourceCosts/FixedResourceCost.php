<?php


namespace App\Domain\Models\ResourceCosts;


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
