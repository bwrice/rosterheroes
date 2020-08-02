<?php

namespace App\Mail;

use App\Domain\Collections\HeroCollection;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlmostReadyForWeek extends SquadNotification
{
    use Queueable, SerializesModels;

    public $subject;
    /**
     * @var Squad
     */
    public $squad;
    /**
     * @var Week
     */
    public $week;
    /**
     * @var HeroCollection
     */
    public $heroesWithoutSpirits;
    /**
     * @var int
     */
    public $questsAvailable;
    /**
     * @var int
     */
    public $sideQuestsAvailable;
    /**
     * @var Collection
     */
    public $campaignStops;

    /**
     * AlmostReadyForWeek constructor.
     * @param Squad $squad
     * @param Week $week
     */
    public function __construct(Squad $squad, Week $week)
    {
        parent::__construct();
        $this->squad = $squad;
        $this->week = $week;
        $this->subject = $this->setSubject();
    }

    protected function setHeroesWithoutSpirits()
    {
        $this->heroesWithoutSpirits = $this->squad->heroes->filter(function (Hero $hero) {
            return is_null($hero->player_spirit_id);
        });
    }

    protected function setQuestAvailability()
    {
        $questsPerWeek = $this->squad->getQuestsPerWeek();
        $sideQuestsPerWeek = $this->squad->getSideQuestsPerQuest() * $questsPerWeek;
        $currentCampaign = $this->squad->getCurrentCampaign();
        if ($currentCampaign) {
            $campaignStops = $currentCampaign->campaignStops;
            $this->questsAvailable = $questsPerWeek - $campaignStops->count();
            $this->sideQuestsAvailable = $sideQuestsPerWeek - $campaignStops->sum(function (CampaignStop $campaignStop) {
                    return $campaignStop->sideQuestResults->count();
                });
        } else {
            $this->questsAvailable = $questsPerWeek;
            $this->sideQuestsAvailable = $sideQuestsPerWeek;
        }
    }

    protected function setSubject()
    {
        $subject = 'Roster Heroes: ';
        $subject .= $this->squad->name . ' almost ready for week. ';
        $hoursUntilLock = $this->week->adventuring_locks_at->diffInHours(now());
        $subject .= 'Campaigns lock in ' . $hoursUntilLock . ' hours!';
        return $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->setHeroesWithoutSpirits();
        $this->campaignStops = $this->squad->getCurrentCampaign() ? $this->squad->getCurrentCampaign()->campaignStops : new Collection();
        $this->setQuestAvailability();
        return $this->markdown('emails.almost-ready-for-week');
    }
}
