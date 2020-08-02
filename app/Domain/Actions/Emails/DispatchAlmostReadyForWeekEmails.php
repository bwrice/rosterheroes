<?php


namespace App\Domain\Actions\Emails;


use App\Domain\Collections\SquadCollection;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\EmailSubscription;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Facades\Admin;
use App\Facades\CurrentWeek;
use App\Mail\AlmostReadyForWeek;
use App\Notifications\BulkEmailsDispatched;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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

                $currentCampaign = $squad->getCurrentCampaign(['campaignStops.sideQuestResults']);

                if (! $currentCampaign) {
                    /*
                     * If there is no current campaign, the squad is consider active based on
                     * if any heroes have spirits attached and because they have no campaign,
                     * we can send them an almost ready email
                     */
                    return $heroesWithoutSpirits->count() !== $squad->heroes->count();
                }

                if (! $this->campaignIsFull($squad, $currentCampaign->campaignStops)) {
                    /*
                     * We know they have a current week's campaign, and if it's not full we can
                     * send them an "almost ready" email
                     */
                    return true;
                }

                /*
                 * Now we know they have a full campaign, so we only send an email if they have heroes
                 * missing spirits
                 */
                return $heroesWithoutSpirits->isNotEmpty();
            });

            $delayCounter = 0;

            $squadsToMail->each(function (Squad $squad) use (&$delayCounter, $now, $currentWeek) {

                $secondsDelay = (20 * $delayCounter + (rand(0, 3)) + rand(0, 20));
                $when = $now->clone()->addSeconds($secondsDelay);
                Mail::to($squad->user)->later($when, new AlmostReadyForWeek($squad, $currentWeek));
                $delayCounter++;
            });

            $count += $squadsToMail->count();
        });

        Admin::notify(new BulkEmailsDispatched('Squad Almost Ready For Week', $count));
        return $count;
    }

    protected function campaignIsFull(Squad $squad, Collection $campaignStops)
    {
        if ($campaignStops->count() < $squad->getQuestsPerWeek()) {
            return false;
        }

        $sideQuestPerQuest = $squad->getSideQuestsPerQuest();
        $unfilledStop = $campaignStops->first(function (CampaignStop $campaignStop) use ($sideQuestPerQuest) {
            return $campaignStop->sideQuestResults->count() < $sideQuestPerQuest;
        });

        return is_null($unfilledStop);
    }
}
