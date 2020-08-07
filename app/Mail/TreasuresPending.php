<?php

namespace App\Mail;

use App\Domain\Models\Squad;

class TreasuresPending extends SquadNotification
{

    public $subject = '';

    /**
     * @var Squad
     */
    public $squad;
    /**
     * @var int
     */
    public $unopenedChestsCount;

    public $title = '';

    public $message = '';

    public function __construct(Squad $squad, int $unopenedChestsCount)
    {
        parent::__construct();
        $this->squad = $squad;
        $this->unopenedChestsCount = $unopenedChestsCount;
        $this->setSubject();
        $this->title = $this->buildTitle();
        $this->message = $this->buildMessage();
    }

    protected function setSubject()
    {
        $this->subject = $this->squad->name . ' has Treasures Chests Waiting to be Opened!';
    }

    protected function buildTitle()
    {
        if ($this->unopenedChestsCount === 1) {
            return 'Treasure Chest Awaiting!';
        }
        return $this->unopenedChestsCount . ' Unopened Chests Awaiting!';
    }

    protected function buildMessage()
    {
        $message = "Your squad, " . $this->squad->name . ', ';
        if ($this->unopenedChestsCount === 1) {
            $message .= "has a treasure chest that was earned from a campaign and still hasn't been opened. ";
        } else {
            $message .= "has " . $this->unopenedChestsCount . " treasure chests that were earned from campaigns and still haven't been opened. ";
        }
        $message .= "There's loot to be had! Visit " . $this->squad->name . "'s command center and " ;

        if ($this->unopenedChestsCount === 1) {
            $message .= "open that chest!";
        } else {
            $message .= "open those chests!";
        }

        return $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.treasures-pending');
    }
}
