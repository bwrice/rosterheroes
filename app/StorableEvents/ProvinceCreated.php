<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class ProvinceCreated implements ShouldBeStored
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $color;
    /**
     * @var array
     */
    public $viewBox;
    /**
     * @var string
     */
    public $vectorPaths;
    /**
     * @var int
     */
    public $continentID;
    /**
     * @var int
     */
    public $territoryID;

    public function __construct(string $name, string $color, array $viewBox, string $vectorPaths, int $continentID, int $territoryID)
    {
        $this->name = $name;
        $this->color = $color;
        $this->viewBox = $viewBox;
        $this->vectorPaths = $vectorPaths;
        $this->continentID = $continentID;
        $this->territoryID = $territoryID;
    }
}
