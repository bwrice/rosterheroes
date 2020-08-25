<?php


namespace App\AStar;


use App\Domain\Models\Json\ViewBox;
use JMGQ\AStar\AbstractNode;

class ProvinceNode extends AbstractNode
{

    /**
     * @var int
     */
    protected $provinceID;
    /**
     * @var array
     */
    protected $borderIDs;
    /**
     * @var ViewBox
     */
    protected $viewBox;

    public function __construct(int $provinceID, array $borderIDs, ViewBox $viewBox)
    {
        $this->provinceID = $provinceID;
        $this->borderIDs = $borderIDs;
        $this->viewBox = $viewBox;
    }

    /**
     * Obtains the node's unique ID
     * @return string
     */
    public function getID()
    {
        return $this->provinceID;
    }

    /**
     * @return array
     */
    public function getBorderIDs(): array
    {
        return $this->borderIDs;
    }

    /**
     * @return ViewBox
     */
    public function getViewBox(): ViewBox
    {
        return $this->viewBox;
    }
}
