<?php


namespace App\Domain\Behaviors\Map;


abstract class MapBehavior
{
    /**
     * @var RealmBehavior
     */
    private $realmBehavior;

    public function __construct(RealmBehavior $realmBehavior)
    {
        $this->realmBehavior = $realmBehavior;
    }

    /**
     * @return string
     */
    public function getRealmColor(): string
    {
        return $this->realmBehavior->getRealmColor();
    }

    /**
     * @return array
     */
    public function getRealmViewBox(): array
    {
        return $this->realmBehavior->getRealmViewBox();
    }
}