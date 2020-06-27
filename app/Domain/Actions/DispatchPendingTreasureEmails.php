<?php


namespace App\Domain\Actions;


use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\Squad;
use App\Facades\Admin;
use App\Mail\TreasuresPending;
use App\Notifications\BulkEmailsDispatched;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class DispatchPendingTreasureEmails
{
    protected $weeksBack = 6;

    public function execute()
    {
        $count = 0;
        $newcomerChestBlueprintID = ChestBlueprint::query()
            ->where('reference_id', '=', ChestBlueprint::NEWCOMER_CHEST)
            ->first()->id;

        Squad::query()->whereHas('unopenedChests', function (Builder $builder) use ($newcomerChestBlueprintID) {

            $builder->where('created_at', '>=', now()->subWeeks($this->weeksBack))
                ->where(function (Builder $builder) use ($newcomerChestBlueprintID) {
                    $builder->where('chest_blueprint_id', '!=', $newcomerChestBlueprintID)
                        ->orWhereNull('chest_blueprint_id');
                });

        })->withCount('unopenedChests')->chunk(200, function (Collection $squads) use (&$count) {

            $squads->each(function (Squad $squad) {
                Mail::to($squad->user)->queue(new TreasuresPending($squad, $squad->unopened_chests_count));
            });
            $count += $squads->count();
        });

        Admin::notify(new BulkEmailsDispatched('Pending Treasures', $count));
        return $count;
    }

    /**
     * @param int $weeksBack
     * @return DispatchPendingTreasureEmails
     */
    public function setWeeksBack(int $weeksBack): DispatchPendingTreasureEmails
    {
        $this->weeksBack = $weeksBack;
        return $this;
    }
}
