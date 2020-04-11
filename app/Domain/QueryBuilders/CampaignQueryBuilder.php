<?php


namespace App\Domain\QueryBuilders;


use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use Illuminate\Database\Eloquent\Builder;

class CampaignQueryBuilder extends Builder
{
    public function forCurrentWeek()
    {
        return $this->where('week_id', '=', CurrentWeek::id());
    }
}
