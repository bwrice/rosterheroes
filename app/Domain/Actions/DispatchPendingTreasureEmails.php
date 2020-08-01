<?php


namespace App\Domain\Actions;


use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\EmailSubscription;
use App\Domain\Models\Squad;
use App\Facades\Admin;
use App\Mail\TreasuresPending;
use App\Notifications\BulkEmailsDispatched;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class DispatchPendingTreasureEmails
{
    public function execute()
    {
        $count = 0;
        $newcomerChestBlueprintID = ChestBlueprint::query()
            ->where('description', '=', 'Newcomer Chest')
            ->first()->id;

        $now = now();

        Squad::query()->whereHas('unopenedChests', function (Builder $builder) use ($newcomerChestBlueprintID) {

            $builder->where(function (Builder $builder) use ($newcomerChestBlueprintID) {
                    $builder->where('chest_blueprint_id', '!=', $newcomerChestBlueprintID)
                        ->orWhereNull('chest_blueprint_id');
                });

        })->whereHas('user', function (Builder $builder) {

            $builder->whereHas('emailSubscriptions', function (Builder $builder) {
                $builder->where('name', '=', EmailSubscription::SQUAD_NOTIFICATIONS);
            });

        })->withCount('unopenedChests')->chunk(200, function (Collection $squads) use (&$count, $now) {

            $delayCounter = 0;
            $squads->each(function (Squad $squad) use ($now, &$delayCounter) {
                // add some randomized time in minutes and seconds to delay emails
                $secondsDelay = (60 * ($delayCounter + rand(0,3))) + rand(1,59);
                $when = $now->clone()->addSeconds($secondsDelay);
                Mail::to($squad->user)->later($when, new TreasuresPending($squad, $squad->unopened_chests_count));
                $delayCounter++;
            });
            $count += $squads->count();
        });

        Admin::notify(new BulkEmailsDispatched('Pending Treasures', $count));
        return $count;
    }
}
