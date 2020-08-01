<?php


namespace App\Domain\Actions\Emails;


use App\Domain\Collections\SquadCollection;
use App\Domain\Models\EmailSubscription;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Facades\Admin;
use App\Facades\CurrentWeek;
use App\Mail\AlmostReadyForWeek;
use App\Notifications\BulkEmailsDispatched;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class DispatchAlmostReadyForWeekEmails
{
    public function execute()
    {
        $now = now();
        $currentWeek = CurrentWeek::get();
        $count = 0;

        Squad::query()->with([
            'heroes'
        ])->whereHas('user', function (Builder $builder) {

            $builder->whereHas('emailSubscriptions', function (Builder $builder) {
                $builder->where('name', '=', EmailSubscription::SQUAD_NOTIFICATIONS);
            });

        })->chunk(200, function (SquadCollection $squads)  use ($now, $currentWeek, &$count) {
            $squadsToMail = $squads->filter(function (Squad $squad) {
                $heroesWithoutSpirits = $squad->heroes->filter(function (Hero $hero) {
                    return is_null($hero->player_spirit_id);
                });
                if ($heroesWithoutSpirits->isNotEmpty()) {
                    return true;
                }
                $currentCampaign = $squad->getCurrentCampaign(['campaignStops.sideQuestResults']);
                if (!$currentCampaign) {
                    return true;
                }
                return false;
            });
            $delayCounter = 0;
            $squadsToMail->each(function (Squad $squad) use (&$delayCounter, $now, $currentWeek) {

                $secondsDelay = (20 * $delayCounter + (rand(0, 3)) + rand(0, 20));
                $when = $now->clone()->addSeconds($secondsDelay);
                Mail::to($squad->user)->later($when, new AlmostReadyForWeek($squad, $currentWeek));
            });

            $count += $squadsToMail->count();
        });

        Admin::notify(new BulkEmailsDispatched('Squad Almost Ready For Week', $count));
        return $count;
    }
}
