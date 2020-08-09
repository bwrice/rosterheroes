<?php

namespace App\Mail;

use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SpiritRemovedFromHero extends SquadNotification
{
    use Queueable, SerializesModels;

    /**
     * @var PlayerSpirit
     */
    public $playerSpirit;
    /**
     * @var Hero
     */
    public $hero;

    /** @var Squad */
    public $squad;

    public function __construct(PlayerSpirit $playerSpirit, Hero $hero)
    {
        parent::__construct();
        $this->playerSpirit = $playerSpirit;
        $this->hero = $hero;
        $this->squad = $hero->squad;
        $this->setSubject();
    }

    protected function setSubject()
    {
        $this->subject = $this->playerSpirit->playerFullName() . ' removed from hero, ' . $this->hero->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.spirit-removed-from-hero');
    }
}
