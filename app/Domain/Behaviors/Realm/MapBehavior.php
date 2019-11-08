<?php


namespace App\Domain\Behaviors\Realm;


use App\Domain\Models\Support\ViewBox;

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
     * @return ViewBox
     */
    public function getViewBox(): ViewBox
    {
        return new ViewBox(
            $this->viewBox['pan_x'],
            $this->viewBox['pan_y'],
            $this->viewBox['zoom_x'],
            $this->viewBox['zoom_y']
        );
    }

}
