<?php


namespace App\Domain\Behaviors\ItemGroup;


abstract class ItemGroup
{
    protected $name = '';

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}
