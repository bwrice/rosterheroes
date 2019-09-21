<?php


namespace App\Domain\Models\ResourceCosts;


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
        return $this->percent . '% of available ' . $this->resourceName;
    }
}
