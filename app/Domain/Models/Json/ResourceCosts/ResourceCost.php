<?php


namespace App\Domain\Models\Json\ResourceCosts;


use App\Domain\Interfaces\SpendsResources;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class ResourceCost implements Arrayable, Jsonable
{
    public const FIXED = 'fixed';
    public const PERCENT_AVAILABLE = 'percent-available';

    /**
     * @var string
     */
    protected $resourceName;

    public function __construct(string $resourceName)
    {
        $this->resourceName = $resourceName;
    }

    abstract public function getDescription(): string;

    public function toArray()
    {
        return [
            'resource' => $this->getResourceName(),
            'description' => $this->getDescription()
        ];
    }

    abstract public function getStaminaCost(SpendsResources $spendsResources): int;

    abstract public function getManCost(SpendsResources $spendsResources): int;

    abstract public function adjustCost($coefficient);

    /**
     * @return string
     */
    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    protected function matchesResourceType($resourceType)
    {
        return $this->resourceName === $resourceType;
    }

    abstract public function getExpectedStaminaCost(): float;

    abstract public function getExpectedManaCost(): float;

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

}
