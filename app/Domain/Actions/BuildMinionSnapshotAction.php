<?php


namespace App\Domain\Actions;


use App\Domain\Models\Minion;
use App\MinionSnapshot;

class BuildMinionSnapshotAction
{
    /**
     * @param Minion $minion
     * @return MinionSnapshot
     */
    public function execute(Minion $minion): MinionSnapshot
    {

    }
}
