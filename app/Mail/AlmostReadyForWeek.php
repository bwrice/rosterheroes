<?php

namespace App\Mail;

use App\Domain\Collections\HeroCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlmostReadyForWeek extends SquadNotification
{
    use Queueable, SerializesModels;
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
     * AlmostReadyForWeek constructor.
     * @param Squad $squad
     * @param Week $week
     */
    public function __construct(Squad $squad, Week $week)
    {
        parent::__construct();
        $this->squad = $squad;
        $this->week = $week;
        $this->heroesWithoutSpirits = $this->setHeroesWithoutSpirits();
    }

    protected function setHeroesWithoutSpirits()
    {
        return $this->squad->heroes->filter(function (Hero $hero) {
            return is_null($hero->player_spirit_id);
        });
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
