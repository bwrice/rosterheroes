<?php


namespace App\Domain\Models\Json;


class ViewBox
{
    /**
     * @var float
     */
    protected $panX;
    /**
     * @var float
     */
    protected $panY;
    /**
     * @var float
     */
    protected $zoomX;
    /**
     * @var float
     */
    protected $zoomY;

    public function __construct(float $panX, float $panY, float $zoomX, float $zoomY)
    {
        $this->panX = $panX;
        $this->panY = $panY;
        $this->zoomX = $zoomX;
        $this->zoomY = $zoomY;
    }

    /**
     * @return float
     */
    public function getPanX(): float
    {
        return $this->panX;
    }

    /**
     * @return float
     */
    public function getPanY(): float
    {
        return $this->panY;
    }

    /**
     * @return float
     */
    public function getZoomX(): float
    {
        return $this->zoomX;
    }

    /**
     * @return float
     */
    public function getZoomY(): float
    {
        return $this->zoomY;
    }

    /**
     * @return float|int
     */
    public function getCenterX()
    {
        return $this->panX + ($this->zoomX/2);
    }

    /**
     * @return float|int
     */
    public function getCenterY()
    {
        return $this->panY + ($this->zoomY/2);
    }
}
