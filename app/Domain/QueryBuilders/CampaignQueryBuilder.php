<?php


namespace App\Domain\QueryBuilders;


use App\Domain\Models\Week;
use Illuminate\Database\Eloquent\Builder;

class CampaignQueryBuilder extends Builder
{
    public function currentForSquad($squadID)
    {
        $week = Week::current();
        return $this->forSquadWeek($squadID, $week->id);
    }

    public function forSquadWeek(int $squadID, int $weekID)
    {
        return $this->where('squad_id', '=', $squadID)
            ->where('week_id', '=', $weekID);
    }
}
