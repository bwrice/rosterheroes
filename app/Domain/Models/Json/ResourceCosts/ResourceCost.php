<?php


namespace App\Domain\Models\Json\ResourceCosts;


use Illuminate\Contracts\Support\Arrayable;

abstract class ResourceCost implements Arrayable
{
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
            'resource' => $this->resourceName,
            'description' => $this->getDescription()
        ];
    }
}
