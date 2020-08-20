<?php


namespace App\Domain\Actions;


use App\AStar\ProvinceNode;
use App\AStar\RealmAStarAlgorithm;
use App\Domain\Collections\ProvinceCollection;
use App\Domain\Models\Province;

class FindPathBetweenProvinces
{
    public function execute(Province $start, Province $end)
    {
        $provinces = Province::query()->get();
        $realmNodes = $provinces->load('borders')->map(function (Province $province) {
            return $this->getProvinceNode($province);
        });

        $algorithm = new RealmAStarAlgorithm($realmNodes);

        $nodes = collect($algorithm->run($this->getProvinceNode($start), $this->getProvinceNode($end)));
        $provinces = $nodes->map(function (ProvinceNode $provinceNode) use ($provinces) {
            return $provinces->firstWhere('id', '=', (int) $provinceNode->getID());
        });

        return new ProvinceCollection($provinces);
    }

    protected function getProvinceNode(Province $province)
    {
        return new ProvinceNode($province->id, $province->borders->pluck('id')->toArray(), $province->getViewBox());
    }
}
