<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Hero;
use App\Jobs\ClearWeeklyPlayerSpiritJob;
use Illuminate\Database\Eloquent\Collection;

class ClearWeeklyPlayerSpiritsFromHeroes
{
    public function execute()
    {
        Hero::query()->whereNotNull('player_spirit_id')->chunk(200, function (Collection $heroes) {
            $heroes->each(function (Hero $hero) {
                ClearWeeklyPlayerSpiritJob::dispatch($hero);
            });
        });
    }
}
