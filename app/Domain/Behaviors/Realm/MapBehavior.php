<?php


namespace App\Domain\Behaviors\Realm;


abstract class MapBehavior
{
    protected $realmColor = '#000';
    protected $viewBox = [];

    /**
     * @return string
     */
    public function getRealmColor(): string
    {
        return $this->realmColor;
    }

    /**
     * @return array
     */
    public function getViewBox(): array
    {
        return $this->viewBox;
    }

}
