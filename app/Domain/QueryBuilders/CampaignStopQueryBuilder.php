<?php


namespace App\Domain\QueryBuilders;


use Illuminate\Database\Eloquent\Builder;

class CampaignStopQueryBuilder extends Builder
{
    public function forQuest(int $questID)
    {
        return $this->where('quest_id', '=', $questID);
    }
}
