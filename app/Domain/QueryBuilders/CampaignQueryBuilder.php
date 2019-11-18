<?php


namespace App\Domain\QueryBuilders;


use App\Domain\Models\Week;
use Illuminate\Database\Eloquent\Builder;

class CampaignQueryBuilder extends Builder
{
    public function forCurrentWeek()
    {
        $week = Week::current();
        return $this->where('week_id', '=', $week->id);
    }
}
