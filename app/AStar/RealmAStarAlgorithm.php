<?php


namespace App\AStar;


use App\Domain\Models\Json\ViewBox;
use Illuminate\Support\Collection;
use JMGQ\AStar\CallbackAlgorithm;
use JMGQ\AStar\Node;

class RealmAStarAlgorithm
{
    /**
     * @var Collection
     */
    protected $provinceNodes;

    public function __construct(Collection $provinceNodes)
    {
        $this->provinceNodes = $provinceNodes;
    }

    /**
     * @param ProvinceNode $provinceNode
     * @return ProvinceNode[]
     */
    public function generateAdjacentNodes(ProvinceNode $provinceNode)
    {
        $borderIDs = $provinceNode->getBorderIDs();
        return $this->provinceNodes->filter(function (ProvinceNode $provinceNode) use ($borderIDs) {
            return in_array($provinceNode->getID(), $borderIDs);
        })->toArray();
    }

    /**
     * @param ProvinceNode $node
     * @param ProvinceNode $adjacent
     * @return integer | float
     */
    public function calculateRealCost(ProvinceNode $node, ProvinceNode $adjacent)
    {
        return $this->calculateCost($node, $adjacent);
    }

    /**
     * @param ProvinceNode $start
     * @param ProvinceNode $end
     * @return integer | float
     */
    public function calculateEstimatedCost(ProvinceNode $start, ProvinceNode $end)
    {
        return $this->calculateCost($start, $end);
    }

    protected function calculateCost(ProvinceNode $start, ProvinceNode $end)
    {
        return $this->getDistanceBetweenViewBoxes($start->getViewBox(), $end->getViewBox());
    }

    protected function getDistanceBetweenViewBoxes(ViewBox $start, ViewBox $end)
    {
        // Calculate distance using Pythagorean Theorem
        $xDelta = abs($start->getCenterX() - $end->getCenterX());
        $yDelta = abs($start->getCenterY() - $end->getCenterY());

        return sqrt( ($xDelta ** 2) + ($yDelta ** 2));
    }

    /**
     * @param ProvinceNode $start
     * @param ProvinceNode $goal
     * @return Node[]
     */
    public function run(ProvinceNode $start, ProvinceNode $goal)
    {
        $algorithm = new CallbackAlgorithm(
            $this,
            'generateAdjacentNodes',
            'calculateRealCost',
            'calculateEstimatedCost'
        );

        return $algorithm->run($start, $goal);
    }
}
