<?php


namespace App\Domain\Behaviors\Continents;


class ContinentBehavior
{
    /**
     * @var string
     */
    private $realmColor;

    public function __construct(string $realmColor)
    {
        $this->realmColor = $realmColor;
    }

    /**
     * @return string
     */
    public function getRealmColor(): string
    {
        return $this->realmColor;
    }
}